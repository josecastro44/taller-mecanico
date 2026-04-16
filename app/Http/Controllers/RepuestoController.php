<?php

namespace App\Http\Controllers;

use App\Models\Repuesto;
use Illuminate\Http\Request;

class RepuestoController extends Controller
{
    public function index(Request $request)
    {
        $buscar = $request->get('buscar');

        $repuestos = Repuesto::when($buscar, function ($query, $buscar) {
            return $query->where(function($q) use ($buscar) {
                $q->where('nombre', 'LIKE', "%{$buscar}%")
                  ->orWhere('codigo', 'LIKE', "%{$buscar}%")
                  ->orWhere('marca_vehiculo', 'LIKE', "%{$buscar}%") // Ahora busca por marca de carro
                  ->orWhere('modelo_vehiculo', 'LIKE', "%{$buscar}%") // Ahora busca por modelo
                  ->orWhere('año_vehiculo', 'LIKE', "%{$buscar}%");   // Ahora busca por año
            });
        })
        ->orderBy('created_at', 'desc')
        ->get();

        return view('repuestos', compact('repuestos', 'buscar'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'marca_vehiculo' => 'nullable|string|max:100',
            'modelo_vehiculo' => 'nullable|string|max:100',
            'año_vehiculo' => 'nullable|string|max:50',
            'costo_adquisicion' => 'required|numeric|min:0',
            'precio_venta' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);

        // Guardamos todo (asegúrate de tener los campos en el $fillable del Modelo)
        Repuesto::create($request->all());

        return redirect()->back()->with('success', '¡Pieza registrada exitosamente!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'marca_vehiculo' => 'nullable|string|max:100',
            'modelo_vehiculo' => 'nullable|string|max:100',
            'año_vehiculo' => 'nullable|string|max:50',
            'costo_adquisicion' => 'required|numeric|min:0',
            'precio_venta' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);

        $repuesto = Repuesto::findOrFail($id);
        $repuesto->update($request->all());

        return redirect()->back()->with('success', '¡Información actualizada correctamente!');
    }
}
