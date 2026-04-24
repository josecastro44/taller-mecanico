<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proveedor;

class ProveedorController extends Controller
{
    public function index(Request $request)
    {
        $query = Proveedor::query();

        // Si hay búsqueda por texto (Empresa, RIF o Contacto)
        if ($request->filled('buscar')) {
            $query->where('nombre', 'like', '%' . $request->buscar . '%')
                  ->orWhere('rif', 'like', '%' . $request->buscar . '%')
                  ->orWhere('contacto', 'like', '%' . $request->buscar . '%');
        }

        // Si hay filtro por categoría
        if ($request->filled('categoria')) {
            $query->where('categoria', $request->categoria);
        }

        $proveedores = $query->orderBy('nombre', 'asc')->paginate(10)->withQueryString();

        return view('proveedores', compact('proveedores'));
    }

    public function guardar(Request $request)
    {
        $datos = $request->validate([
            'nombre'    => 'required|string|unique:proveedores',
            'rif'       => 'nullable|string',
            'contacto'  => 'nullable|string',
            'telefono'  => 'nullable|string',
            'categoria' => 'nullable|string',
        ]);

        Proveedor::create($datos);
        return back()->with('exito', '¡Proveedor registrado exitosamente!');
    }

    public function actualizar(Request $request, $id)
    {
        $proveedor = Proveedor::findOrFail($id);

        $datos = $request->validate([
            'nombre'    => 'required|string|unique:proveedores,nombre,' . $proveedor->id,
            'rif'       => 'nullable|string',
            'contacto'  => 'nullable|string',
            'telefono'  => 'nullable|string',
            'categoria' => 'nullable|string',
        ]);

        $proveedor->update($datos);
        return back()->with('exito', '¡Datos del proveedor actualizados!');
    }

    public function eliminar($id)
    {
        Proveedor::findOrFail($id)->delete();
        return back()->with('exito', '¡Proveedor eliminado del directorio!');
    }
}