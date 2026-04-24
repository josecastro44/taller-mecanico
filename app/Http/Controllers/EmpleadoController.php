<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empleado;
use App\Models\OrdenServicio; // Importamos las órdenes para contar los servicios del mes
use Carbon\Carbon;
use App\Models\User;

class EmpleadoController extends Controller
{
    public function index()
    {
        $empleados = Empleado::all();
        
        // Matemáticas del Dashboard
        $mecanicosActivos = $empleados->count();
        // Por ahora, la nómina pendiente será la suma de los sueldos base
        $nominaPendiente = $empleados->sum('sueldo_base'); 
        
        // Contamos cuántas órdenes de servicio se han creado este mes
        $serviciosRealizados = OrdenServicio::whereMonth('created_at', Carbon::now()->month)->count();

        return view('empleados', compact('empleados', 'mecanicosActivos', 'nominaPendiente', 'serviciosRealizados'));
    }

public function guardar(Request $request)
    {
        $datos = $request->validate([
            'nombre'       => 'required|string|max:100',
            'cedula'       => 'required|string|unique:empleados',
            'telefono'     => 'nullable|string',
            'especialidad' => 'required|string',
            'sueldo_base'  => 'required|numeric|min:0',
            'comision'     => 'required|numeric|min:0|max:100',
        ]);

        $empleado = Empleado::create($datos);

        // ¡MAGIA AUTOMÁTICA! Le creamos su usuario para que inicie sesión
        // Tomamos su primer nombre para el correo (Ej: jose@taller.com)
        $primerNombre = strtolower(explode(' ', $empleado->nombre)[0]);
        $correoAsignado = $primerNombre . '@taller.com';

        User::firstOrCreate(
            ['email' => $correoAsignado],
            [
                'name' => $empleado->nombre, // El nombre debe coincidir exacto
                'password' => bcrypt('123456'), // Clave por defecto
                'rol' => ($empleado->especialidad == 'Recepcionista / Administrativo') ? 'administrador' : 'mecanico'
            ]
        );

        return back()->with('exito', '¡Empleado guardado! Su acceso es -> Correo: ' . $correoAsignado . ' | Clave: 123456');
    }

    public function actualizar(Request $request, $id)
    {
        $empleado = Empleado::findOrFail($id);

        $datos = $request->validate([
            'nombre'       => 'required|string|max:100',
            'cedula'       => 'required|string|unique:empleados,cedula,' . $empleado->id,
            'telefono'     => 'nullable|string',
            'especialidad' => 'required|string',
            'sueldo_base'  => 'required|numeric|min:0',
            'comision'     => 'required|numeric|min:0|max:100',
        ]);

        $empleado->update($datos);
        return back()->with('exito', '¡Datos del empleado actualizados!');
    }

    public function eliminar($id)
    {
        Empleado::findOrFail($id)->delete();
        return back()->with('exito', '¡Empleado eliminado del sistema!');
    }
}