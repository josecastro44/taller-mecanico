<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Repuesto;

class RepuestoController extends Controller
{
    // Muestra la vista con la lista de repuestos
    public function index()
    {
        $repuestos = Repuesto::all();
        return view('repuestos', compact('repuestos'));
    }

    // Guarda el nuevo repuesto
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'costo_adquisicion' => 'required|numeric',
            'precio_venta' => 'required|numeric',
            'stock' => 'required|integer',
        ]);

        Repuesto::create($request->all());

        return redirect()->route('repuestos.index');
    }

    public function reporte()
    {
    $repuestos = Repuesto::all(); // O Repuesto::orderBy('nombre')->get();
    return view('repuestos.reporte', compact('repuestos'));
    }
}
