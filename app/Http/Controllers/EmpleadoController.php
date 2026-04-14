<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empleado;

class EmpleadoController extends Controller
{
    public function index()
    {
        // Por ahora solo mostramos la vista (luego le enviaremos la lista de empleados)
        return view('empleados');
    }

    public function guardar(Request $request)
    {
        // 1. Validar los datos del Modal
        $datos = $request->validate([
            'nombre'       => 'required|string|max:100',
            'cedula'       => 'required|string',
            'telefono'     => 'nullable|string',
            'especialidad' => 'required|string',
            'comision'     => 'required|numeric|min:0|max:100',
        ]);

        // 2. Guardar en la Base de Datos
        Empleado::create($datos);

        // 3. Devolver a la pantalla con un mensaje de éxito
        return back()->with('exito', '¡Empleado registrado con éxito en la Base de Datos!');
    }
}