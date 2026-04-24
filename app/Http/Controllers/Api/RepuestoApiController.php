<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Repuesto;

class RepuestoApiController extends Controller
{
    /**
     * Lista todos los repuestos disponibles con conversión a Bolívares.
     */
    public function index()
    {
        // Tasa del día (En un futuro se conectaría a una tabla de Configuración o API del BCV)
        $tasaBcv = 38.45; 

        // Usamos Eloquent para traer solo los que tienen stock y mapeamos la colección
        $repuestos = Repuesto::where('stock', '>', 0)
            ->get()
            ->map(function ($repuesto) use ($tasaBcv) {
                return [
                    'id' => $repuesto->id,
                    'codigo' => $repuesto->codigo ?? 'N/A',
                    'nombre' => $repuesto->nombre,
                    'precio_usd' => number_format($repuesto->precio, 2, '.', ''),
                    'precio_bs' => number_format($repuesto->precio * $tasaBcv, 2, '.', ''),
                    'stock' => $repuesto->stock,
                    'ultima_actualizacion' => $repuesto->updated_at->format('Y-m-d H:i:s')
                ];
            });

        return response()->json([
            'status' => 'success',
            'moneda_base' => 'USD',
            'tasa_bcv_aplicada' => $tasaBcv,
            'total_resultados' => $repuestos->count(),
            'data' => $repuestos
        ], 200);
    }

    /**
     * Registra un nuevo repuesto en el inventario vía API.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'codigo' => 'nullable|string|max:50|unique:repuestos',
            'nombre' => 'required|string|max:255',
            'precio' => 'required|numeric|min:0.1',
            'stock' => 'required|integer|min:0'
        ]);

        $repuesto = Repuesto::create($validatedData);

        return response()->json([
            'status' => 'success',
            'mensaje' => 'Inventario actualizado correctamente.',
            'data' => $repuesto
        ], 201);
    }
}