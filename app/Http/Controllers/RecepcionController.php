<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\Vehiculo;
use App\Models\OrdenServicio;
use App\Models\Empleado;
use App\Models\Servicio;

class RecepcionController extends Controller
{
    public function index()
    {
        $servicios = Servicio::all();
        $mecanicos = Empleado::where('especialidad', '!=', 'Recepcionista / Administrativo')->get();
        
        $ordenes = OrdenServicio::with(['vehiculo', 'mecanico'])
                    ->orderBy('created_at', 'desc')
                    ->get();

        return view('recepcion', compact('servicios', 'mecanicos', 'ordenes'));
    }

    public function guardar(Request $request)
    {
        $datosValidados = $request->validate([
            'cedula'        => 'required|min:7|max:10',
            'nombre'        => 'required|string|max:100',
            'telefono'      => 'required|min:10',
            'placa'         => 'required|string|min:6|max:8',
            'marca'         => 'required|string',
            'modelo'        => 'required|string',
            'diagnostico'   => 'required|min:10',
            'mecanico_id'   => 'nullable|exists:empleados,id',
            'servicios'     => 'required|array', 
            'servicios.*'   => 'exists:servicios,id',
            'tipo_vehiculo' => 'required|in:sencillo,alta_gama,carga_pesada',
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
            'prioridad'   => 'Normal',
            'fecha_recepcion' => now()
        ]);

        // FIX: Seleccionar el precio correcto según tipo de vehículo
        $tipoVehiculo = $datosValidados['tipo_vehiculo'];
        $serviciosSeleccionados = Servicio::whereIn('id', $request->servicios)->get();
        $datosPivote = [];
        
        foreach ($serviciosSeleccionados as $servicio) {
            // Mapear tipo de vehículo al campo de precio correspondiente
            $precio = match($tipoVehiculo) {
                'alta_gama'     => $servicio->precio_alta_gama,
                'carga_pesada'  => $servicio->precio_carga_pesada,
                default         => $servicio->precio_sencillo,
            };
            $datosPivote[$servicio->id] = ['precio_cobrado' => $precio]; 
        }

        $orden->servicios()->attach($datosPivote);

        return back()->with('exito', '¡La orden de servicio fue creada y vinculada correctamente!');
    }

    public function monitor()
    {
        $ordenes = OrdenServicio::with(['vehiculo', 'mecanico'])
                    ->orderBy('created_at', 'desc')
                    ->get();

        return view('monitor', compact('ordenes'));
    }
}