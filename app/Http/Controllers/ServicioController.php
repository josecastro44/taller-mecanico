<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Servicio;

class ServicioController extends Controller
{
    public function index(Request $request)
    {
        // 1. Iniciamos la consulta
        $query = Servicio::query();

        // 2. Si el usuario escribió en el buscador, filtramos
        if ($request->filled('buscar')) {
            $query->where('descripcion', 'like', '%' . $request->buscar . '%')
                  ->orWhere('codigo', 'like', '%' . $request->buscar . '%');
        }

        // 3. Paginamos los resultados (y le decimos que mantenga la búsqueda al cambiar de página)
        $servicios = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString(); 
        
        return view('servicios', compact('servicios'));
    }

    public function guardar(Request $request)
    {
        $datos = $request->validate([
            'codigo'              => 'required|string|unique:servicios',
            'descripcion'         => 'required|string',
            'precio_sencillo'     => 'required|numeric|min:0',
            'precio_alta_gama'    => 'required|numeric|min:0',
            'precio_carga_pesada' => 'required|numeric|min:0',
        ]);

        Servicio::create($datos);
        return back()->with('exito', '¡Servicio registrado exitosamente!');
    }

    public function actualizar(Request $request, $id)
    {
        $servicio = Servicio::findOrFail($id);

        $datos = $request->validate([
            // Ignoramos el unique para el código actual
            'codigo'              => 'required|string|unique:servicios,codigo,' . $servicio->id,
            'descripcion'         => 'required|string',
            'precio_sencillo'     => 'required|numeric|min:0',
            'precio_alta_gama'    => 'required|numeric|min:0',
            'precio_carga_pesada' => 'required|numeric|min:0',
        ]);

        $servicio->update($datos);
        return back()->with('exito', '¡Servicio actualizado correctamente!');
    }

    public function eliminar($id)
    {
        $servicio = Servicio::findOrFail($id);
        $servicio->delete();
        
        return back()->with('exito', '¡Servicio eliminado del catálogo!');
    }
}