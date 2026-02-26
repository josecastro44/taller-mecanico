<?php

namespace App\Http\Controllers;
use App\Models\Servicio;

use Illuminate\Http\Request;

class ServicioController extends Controller
{
    
    public function index()
    {
        $servicios = Servicio::all(); // Trae los datos de tu tabla
        return view('servicios.index', compact('servicios'));
    }
    public function calcularPrecio($servicioId, $tipoVehiculo)
{
    // Buscamos el servicio en la tabla que ya tienes
    $servicio = Servicio::find($servicioId);
    $precio = $servicio->precio_base;

    // Lógica de Variaciones por Categoría
    if (!$servicio->es_precio_manual) {
        if ($tipoVehiculo == 'Carga Pesada') {
            $precio *= 1.5; // Aumento del 50%
        } elseif ($tipoVehiculo == 'Alta Gama') {
            $precio *= 1.2; // Aumento del 20%
        }
    }

    return $precio;
}
}
