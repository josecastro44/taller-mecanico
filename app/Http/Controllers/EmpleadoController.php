<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empleado;
use App\Models\OrdenServicio;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class EmpleadoController extends Controller
{
    public function index()
    {
        $empleados = Empleado::with('user')->get();
        
        $mecanicosActivos = $empleados->count();
        $nominaPendiente = $empleados->sum('sueldo_base'); 
        
        // Contamos cuántas órdenes de servicio se han completado este mes
        $serviciosRealizados = OrdenServicio::whereMonth('created_at', Carbon::now()->month)->count();

        // Calcular comisiones reales por mecánico este mes
        $inicioMes = Carbon::now()->startOfMonth();
        $comisionesPorEmpleado = [];
        
        foreach ($empleados as $empleado) {
            // Buscar órdenes finalizadas/entregadas de este mecánico en el mes
            $ordenesDelMes = OrdenServicio::with('servicios')
                ->where('mecanico_id', $empleado->id)
                ->whereIn('estado', ['Finalizado', 'Entregado'])
                ->where('updated_at', '>=', $inicioMes)
                ->get();
            
            // Sumar el total de mano de obra de esas órdenes
            $totalManoObra = 0;
            foreach ($ordenesDelMes as $orden) {
                foreach ($orden->servicios as $servicio) {
                    $totalManoObra += $servicio->pivot->precio_cobrado ?? 0;
                }
            }
            
            // La comisión es el porcentaje sobre la mano de obra
            $comision = ($empleado->comision / 100) * $totalManoObra;
            $comisionesPorEmpleado[$empleado->id] = [
                'mano_obra' => $totalManoObra,
                'comision_ganada' => $comision,
                'ordenes_completadas' => $ordenesDelMes->count(),
            ];
        }

        // Nómina total = sueldos base + comisiones ganadas
        $totalComisiones = collect($comisionesPorEmpleado)->sum('comision_ganada');
        $nominaTotal = $nominaPendiente + $totalComisiones;

        return view('empleados', compact(
            'empleados', 'mecanicosActivos', 'nominaPendiente', 
            'serviciosRealizados', 'comisionesPorEmpleado', 'nominaTotal', 'totalComisiones'
        ));
    }

    public function guardar(Request $request)
    {
        $datos = $request->validate([
            'nombre'       => 'required|string|max:100',
            'cedula'       => 'required|string|unique:empleados',
            'telefono'     => 'nullable|string',
            'direccion'    => 'nullable|string',
            'especialidad' => 'required|string',
            'sueldo_base'  => 'required|numeric|min:0',
            'comision'     => 'required|numeric|min:0|max:100',
            'correo'       => 'required|email|unique:users,email',
            'clave'        => 'nullable|string|min:6',
            'rol_sistema'  => 'required|string|in:mecanico,administrador,gerente',
        ]);

        $empleado = Empleado::create([
            'nombre'       => $datos['nombre'],
            'cedula'       => $datos['cedula'],
            'telefono'     => $datos['telefono'],
            'direccion'    => $datos['direccion'],
            'especialidad' => $datos['especialidad'],
            'sueldo_base'  => $datos['sueldo_base'],
            'comision'     => $datos['comision'],
        ]);

        $clave = !empty($datos['clave']) ? $datos['clave'] : '123456';

        $user = User::create([
            'name' => $empleado->nombre,
            'email' => $datos['correo'],
            'password' => bcrypt($clave),
            'rol' => $datos['rol_sistema']
        ]);

        $empleado->user_id = $user->id;
        $empleado->save();

        return back()->with('exito', '¡Empleado guardado! Su acceso es -> Correo: ' . $datos['correo'] . ' | Clave: ' . $clave);
    }

    public function actualizar(Request $request, $id)
    {
        $empleado = Empleado::with('user')->findOrFail($id);

        $datos = $request->validate([
            'nombre'       => 'required|string|max:100',
            'cedula'       => 'required|string|unique:empleados,cedula,' . $empleado->id,
            'telefono'     => 'nullable|string',
            'direccion'    => 'nullable|string',
            'especialidad' => 'required|string',
            'sueldo_base'  => 'required|numeric|min:0',
            'comision'     => 'required|numeric|min:0|max:100',
            'correo'       => 'required|email|unique:users,email,' . ($empleado->user_id ?? 'NULL'),
            'clave'        => 'nullable|string|min:6',
            'rol_sistema'  => 'required|string|in:mecanico,administrador,gerente',
        ]);

        $empleado->update([
            'nombre'       => $datos['nombre'],
            'cedula'       => $datos['cedula'],
            'telefono'     => $datos['telefono'],
            'direccion'    => $datos['direccion'],
            'especialidad' => $datos['especialidad'],
            'sueldo_base'  => $datos['sueldo_base'],
            'comision'     => $datos['comision'],
        ]);

        if ($empleado->user) {
            $userDatos = [
                'name' => $datos['nombre'],
                'email' => $datos['correo'],
                'rol' => $datos['rol_sistema']
            ];
            if (!empty($datos['clave'])) {
                $userDatos['password'] = bcrypt($datos['clave']);
            }
            $empleado->user->update($userDatos);
        } else {
            // Si por alguna razón no tenía usuario, se lo creamos
            $clave = !empty($datos['clave']) ? $datos['clave'] : '123456';
            $user = User::create([
                'name' => $empleado->nombre,
                'email' => $datos['correo'],
                'password' => bcrypt($clave),
                'rol' => $datos['rol_sistema']
            ]);
            $empleado->user_id = $user->id;
            $empleado->save();
        }

        return back()->with('exito', '¡Datos del empleado actualizados!');
    }

    public function eliminar($id)
    {
        $empleado = Empleado::findOrFail($id);
        
        // FIX: Eliminar también el User vinculado para que no quede huérfano
        if ($empleado->user_id) {
            User::where('id', $empleado->user_id)->delete();
        }
        
        $empleado->delete();
        return back()->with('exito', '¡Empleado y su acceso al sistema eliminados!');
    }

    public function imprimirReporte()
    {
        $empleados = Empleado::with('user')->orderBy('nombre')->get();
        $pdf = \PDF::loadView('pdfs.reporte_empleados', compact('empleados'));
        $pdf->setPaper('letter', 'portrait');
        return $pdf->stream('reporte_empleados.pdf');
    }
}