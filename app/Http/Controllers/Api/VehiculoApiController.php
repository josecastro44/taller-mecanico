<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vehiculo;

class VehiculoApiController extends Controller
{
    /**
     * Obtiene el catálogo de vehículos registrados, incluyendo los datos de su dueño.
     */
    public function index()
    {
        // Usamos Eager Loading (with) para optimizar consultas y evitar el problema N+1
        $vehiculos = Vehiculo::with('cliente:id,nombre,telefono')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return response()->json([
            'status' => 'success',
            'mensaje' => 'Directorio de vehículos obtenido con éxito.',
            'paginacion' => [
                'total' => $vehiculos->total(),
                'pagina_actual' => $vehiculos->currentPage(),
                'ultima_pagina' => $vehiculos->lastPage(),
            ],
            'data' => $vehiculos->items()
        ], 200);
    }

    /**
     * Búsqueda específica de un vehículo por su número de placa.
     */
    public function buscarPorPlaca($placa)
    {
        // Limpiamos y estandarizamos el formato de la placa recibida
        $placaLimpia = strtoupper(trim($placa));

        $vehiculo = Vehiculo::with('cliente')->where('placa', $placaLimpia)->first();

        if (!$vehiculo) {
            return response()->json([
                'status' => 'error',
                'mensaje' => 'Vehículo no encontrado en los registros de AutoSys.'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'id_vehiculo' => $vehiculo->id,
                'placa' => $vehiculo->placa,
                'marca_modelo' => $vehiculo->marca . ' ' . $vehiculo->modelo,
                'año' => $vehiculo->año,
                'propietario' => $vehiculo->cliente ? $vehiculo->cliente->nombre : 'Sin asignar',
                'contacto_propietario' => $vehiculo->cliente ? $vehiculo->cliente->telefono : 'N/A',
                'endpoint_historial' => url("/api/vehiculos/{$vehiculo->id}/historial-ordenes")
            ]
        ], 200);
    }
}