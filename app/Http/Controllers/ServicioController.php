<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Servicio;

class ServicioController extends Controller
{
    public function index()
    {
        // 1. Obtener todos los servicios de la base de datos
        // Usamos paginate para que la tabla no se vuelva infinita
        $servicios = Servicio::paginate(10); 
        
        // 2. Enviar los datos a la vista
        return view('servicios', compact('servicios'));
    }

    public function guardar(Request $request)
    {
        // 1. Validar los datos del formulario (Modal)
        $datos = $request->validate([
            'codigo'              => 'required|string|unique:servicios',
            'descripcion'         => 'required|string',
            'precio_sencillo'     => 'required|numeric|min:0',
            'precio_alta_gama'    => 'required|numeric|min:0',
            'precio_carga_pesada' => 'required|numeric|min:0',
            'categoria'           => 'nullable|string',
        ]);

        // 2. Guardar en la Base de Datos
        Servicio::create($datos);

        // 3. Devolver con mensaje de éxito
        return back()->with('exito', '¡Servicio registrado exitosamente!');
    }
}