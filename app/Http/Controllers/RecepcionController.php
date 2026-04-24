<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\Vehiculo;
use App\Models\OrdenServicio;
use App\Models\Empleado; // <-- Ahora leemos de Empleados
use App\Models\Servicio; // <-- Traemos los servicios

class RecepcionController extends Controller
{
public function index()
    {
        // Traemos los datos para el formulario de arriba
        $servicios = \App\Models\Servicio::all();
        $mecanicos = \App\Models\Empleado::where('especialidad', '!=', 'Recepcionista / Administrativo')->get();
        
        // Traemos TODAS las órdenes para el Monitor de Taller abajo
        $ordenes = \App\Models\OrdenServicio::with(['vehiculo', 'mecanico'])
                    ->orderBy('created_at', 'desc')
                    ->get();

        return view('recepcion', compact('servicios', 'mecanicos', 'ordenes'));
    }

public function guardar(Request $request)
    {
        $datosValidados = $request->validate([
            'cedula'      => 'required|min:7|max:10',
            'nombre'      => 'required|string|max:100',
            'telefono'    => 'required|min:10',
            'placa'       => 'required|string|min:6|max:8',
            'marca'       => 'required|string',
            'modelo'      => 'required|string',
            'diagnostico' => 'required|min:10',
            'mecanico_id' => 'nullable|exists:empleados,id',
            'servicios'   => 'required|array', 
            'servicios.*' => 'exists:servicios,id'
        ]);

        $cliente = Cliente::firstOrCreate(
            ['cedula_rut' => $datosValidados['cedula']], 
            ['nombre' => $datosValidados['nombre'], 'telefono' => $datosValidados['telefono']]
        );

        $vehiculo = Vehiculo::firstOrCreate(
            ['placa' => $datosValidados['placa']],
            [
                'cliente_id' => $cliente->id,
                'marca' => $datosValidados['marca'],
                'modelo' => $datosValidados['modelo'],
                'anio' => date('Y'),
                'kilometraje' => $request->kilometraje ?? 0
            ]
        );

        $orden = OrdenServicio::create([
            'vehiculo_id' => $vehiculo->id,
            'mecanico_id' => $datosValidados['mecanico_id'], 
            'diagnostico' => $datosValidados['diagnostico'],
            'estado'      => 'En Espera',
            'prioridad'   => 'Normal' 
        ]);

        // EL FIX: Buscamos los servicios seleccionados y armamos el arreglo con sus precios congelados
        $serviciosSeleccionados = Servicio::whereIn('id', $request->servicios)->get();
        $datosPivote = [];
        
        foreach ($serviciosSeleccionados as $servicio) {
            // 'precio_cobrado' guardará el precio que tiene el servicio HOY
            $datosPivote[$servicio->id] = ['precio_cobrado' => $servicio->precio ?? 0]; 
        }

        // Guardamos todo en la tabla pivote de un solo golpe
        $orden->servicios()->attach($datosPivote);

        return back()->with('exito', '¡La orden de servicio fue creada y vinculada correctamente!');
    }

    public function monitor()
    {
        // Traemos TODAS las órdenes para el Monitor de Taller
        $ordenes = \App\Models\OrdenServicio::with(['vehiculo', 'mecanico'])
                    ->orderBy('created_at', 'desc')
                    ->get();

        return view('monitor', compact('ordenes'));
    }
}