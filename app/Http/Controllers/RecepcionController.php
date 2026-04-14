<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// Importamos los modelos de tu base de datos
use App\Models\Cliente;
use App\Models\Vehiculo;
use App\Models\OrdenServicio;

class RecepcionController extends Controller
{
    public function index()
    {
        return view('recepcion');
    }

    public function guardar(Request $request)
    {
        // 1. LA ADUANA (Validación que ya hicimos)
        $datosValidados = $request->validate([
            'cedula'   => 'required|min:7|max:10',
            'nombre'   => 'required|string|max:100',
            'telefono' => 'required|min:10',
            'placa'    => 'required|string|min:6|max:8',
            'marca'    => 'required|string',
            'modelo'   => 'required|string',
            'diagnostico' => 'required|min:10',
        ], [
            'required' => 'Este campo es obligatorio.',
            'min'      => 'El texto es muy corto.',
            'max'      => 'El texto es muy largo.'
        ]);

        // 2. GUARDAR CLIENTE (firstOrCreate: Si ya existe por la cédula lo trae, si no, lo crea nuevo)
        $cliente = Cliente::firstOrCreate(
            ['cedula_rut' => $datosValidados['cedula']], 
            ['nombre' => $datosValidados['nombre'], 'telefono' => $datosValidados['telefono']]
        );

// 3. GUARDAR VEHÍCULO (Asignado al ID de ese cliente)
        $vehiculo = Vehiculo::firstOrCreate(
            ['placa' => $datosValidados['placa']],
            [
                'cliente_id' => $cliente->id,
                'marca' => $datosValidados['marca'],
                'modelo' => $datosValidados['modelo'],
                'anio' => date('Y'), // <--- EL FIX ESTÁ AQUÍ (Le ponemos el año actual por defecto)
                'kilometraje' => $request->kilometraje ?? 0
            ]
        );

        // 4. CREAR LA ORDEN DE SERVICIO
        OrdenServicio::create([
            'vehiculo_id' => $vehiculo->id,
            'diagnostico' => $datosValidados['diagnostico'],
            'estado'      => 'En Espera', // Arranca por defecto esperando al mecánico
            // Si tu base de datos tiene la columna prioridad, la ponemos:
            'prioridad'   => 'Normal' 
        ]);

        // 5. REDIRIGIR CON ÉXITO
        return back()->with('exito', '¡La orden de servicio fue guardada exitosamente en la Base de Datos!');
    }
}