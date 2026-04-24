<?php

namespace App\Http\Controllers;

use App\Models\Repuesto;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class RepuestoController extends Controller
{
    /**
     * Muestra la lista de repuestos con búsqueda, estadísticas y paginación.
     */
    public function index(Request $request)
    {
        $buscar = $request->get('buscar');

        // Lógica de búsqueda combinada con paginación
        $repuestos = Repuesto::when($buscar, function ($query, $buscar) {
            return $query->where(function($q) use ($buscar) {
                $q->where('nombre', 'LIKE', "%{$buscar}%")
                  ->orWhere('codigo', 'LIKE', "%{$buscar}%")
                  ->orWhere('marca_vehiculo', 'LIKE', "%{$buscar}%")
                  ->orWhere('modelo_vehiculo', 'LIKE', "%{$buscar}%")
                  ->orWhere('año_vehiculo', 'LIKE', "%{$buscar}%");
            });
        })
        ->orderBy('created_at', 'desc')
        ->paginate(10); // Paginación de 10 en 10 para tu diseño

        // Estadísticas para las tarjetas superiores
        $totalRepuestos = Repuesto::count();
        $stockCritico = Repuesto::where('stock', '<=', 5)->count(); 

        return view('repuestos', compact('repuestos', 'buscar', 'totalRepuestos', 'stockCritico'));
    }

    /**
     * Registra una nueva pieza en el inventario.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre'            => 'required|string|max:255',
            'codigo'            => 'required|string|unique:repuestos,codigo',
            'marca_vehiculo'    => 'nullable|string|max:100',
            'modelo_vehiculo'   => 'nullable|string|max:100',
            'año_vehiculo'      => 'nullable|string|max:50',
            'costo_adquisicion' => 'required|numeric|min:0',
            'precio_venta'      => 'required|numeric|min:0',
            'stock'             => 'required|integer|min:0',
        ]);

        Repuesto::create($request->all());

        return redirect()->back()->with('exito', '¡Pieza registrada exitosamente en CTR!');
    }

    /**
     * Actualiza la información de un repuesto existente.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre'            => 'required|string|max:255',
            'marca_vehiculo'    => 'nullable|string|max:100',
            'modelo_vehiculo'   => 'nullable|string|max:100',
            'año_vehiculo'      => 'nullable|string|max:50',
            'costo_adquisicion' => 'required|numeric|min:0',
            'precio_venta'      => 'required|numeric|min:0',
            'stock'             => 'required|integer|min:0',
        ]);

        $repuesto = Repuesto::findOrFail($id);
        $repuesto->update($request->all());

        return redirect()->back()->with('exito', '¡Información actualizada correctamente!');
    }

    /**
     * Genera el PDF del Inventario General.
     */
    public function imprimirGeneral()
    {
        $repuestos = Repuesto::orderBy('nombre', 'asc')->get();
        $fecha = Carbon::now()->format('d/m/Y h:i A');
        
        // Cálculo del valor total del almacén
        $totalInversion = 0;
        foreach($repuestos as $rep) {
            // Nota: Se usa costo_adquisicion para que coincida con tu validación
            $totalInversion += ($rep->costo_adquisicion * $rep->stock);
        }

        $pdf = Pdf::loadView('pdfs.inventario_general', compact('repuestos', 'fecha', 'totalInversion'));
        return $pdf->stream('Reporte_Inventario_General_CTR.pdf');
    }

    /**
     * Genera la ficha técnica individual (PDF pequeño).
     */
    public function imprimirIndividual($id)
    {
        $repuesto = Repuesto::findOrFail($id);
        $pdf = Pdf::loadView('pdfs.repuesto_individual', compact('repuesto'));
        
        // Formato tipo etiqueta
        $pdf->setPaper([0, 0, 300, 400], 'portrait'); 
        return $pdf->stream('Ficha_'.$repuesto->codigo.'.pdf');
    }
}