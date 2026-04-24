<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrdenServicio;
use App\Models\Repuesto;
use App\Models\Empleado;
use Illuminate\Support\Facades\Auth;

class MecanicoController extends Controller
{
    public function index()
    {
        $mecanico = Empleado::where('nombre', Auth::user()->name)->first();

        if (!$mecanico) {
            return redirect('/')->withErrors(['No tienes un perfil de empleado asignado. Verifica que tu nombre de usuario coincida con el de empleado.']);
        }

        $pendientes = OrdenServicio::with('vehiculo')->where('mecanico_id', $mecanico->id)->where('estado', 'En Espera')->get();
        
        // Usamos la palabra oficial de tu BD: 'En Reparación'
        $enProceso = OrdenServicio::with('vehiculo')->where('mecanico_id', $mecanico->id)->where('estado', 'En Reparación')->get();
        
        // Usamos la palabra oficial de tu BD: 'Finalizado'
        $terminadosHoy = OrdenServicio::where('mecanico_id', $mecanico->id)->where('estado', 'Finalizado')->whereDate('updated_at', today())->count();

        $repuestos = Repuesto::where('stock', '>', 0)->get();

        return view('mecanico', compact('pendientes', 'enProceso', 'terminadosHoy', 'repuestos'));
    }

    public function cambiarEstado($id, $estado)
    {
        $orden = OrdenServicio::findOrFail($id);
        
        // Lista VIP estricta con las palabras de tu base de datos
        $estadosPermitidos = ['En Espera', 'En Reparación', 'Finalizado'];
        
        if (in_array($estado, $estadosPermitidos)) {
            $orden->estado = $estado;
            $orden->save();
            
            $mensaje = ($estado == 'En Reparación') ? '¡Reparación iniciada!' : '¡Vehículo reparado con éxito!';
            return back()->with('exito', $mensaje);
        }

        return back()->withErrors(['Estado no válido.']);
    }

    public function agregarRepuesto(Request $request, $id)
    {
        $request->validate([
            'repuesto_id' => 'required|exists:repuestos,id',
            'cantidad'    => 'required|integer|min:1'
        ]);

        $orden = OrdenServicio::findOrFail($id);
        $repuesto = Repuesto::findOrFail($request->repuesto_id);

        if ($request->cantidad > $repuesto->stock) {
            return back()->withErrors(['Stock insuficiente. Quedan: ' . $repuesto->stock]);
        }

        $repuesto->stock -= $request->cantidad;
        $repuesto->save();

        $precioFinal = $repuesto->precio ?? $repuesto->precio_venta ?? 0;
        $orden->repuestos()->attach($repuesto->id, [
            'cantidad'        => $request->cantidad,
            'precio_unitario' => $precioFinal
        ]);

        return back()->with('exito', '¡Repuesto descontado del inventario y agregado a la Orden!');
    }

    public function historial()
    {
        $mecanico = Empleado::where('nombre', Auth::user()->name)->first();

        if (!$mecanico) {
            return redirect('/')->withErrors(['No tienes un perfil de empleado asignado.']);
        }

        // Traemos las órdenes que este mecánico ya terminó. 
        // Incluimos 'Entregado' por si el gerente ya le dio el carro al cliente en Recepción.
        $historial = OrdenServicio::with(['vehiculo', 'servicios'])
                        ->where('mecanico_id', $mecanico->id)
                        ->whereIn('estado', ['Finalizado', 'Entregado'])
                        ->orderBy('updated_at', 'desc') // Los más recientes primero
                        ->get();

        // Calculamos las estadísticas reales
        $terminadosMes = $historial->where('updated_at', '>=', now()->startOfMonth())->count();
        
        // Contamos cuántos servicios en total ha hecho (para reemplazar el cuadro de "Horas")
        $totalServicios = 0;
        foreach($historial as $orden) {
            $totalServicios += $orden->servicios->count();
        }

        return view('mecanico_historial', compact('historial', 'terminadosMes', 'totalServicios', 'mecanico'));
    }

    public function insumos()
    {
        $mecanico = Empleado::where('nombre', Auth::user()->name)->first();

        if (!$mecanico) {
            return redirect('/')->withErrors(['No tienes un perfil de empleado asignado.']);
        }

        // Buscamos todas las órdenes de este mecánico y los repuestos que usó
        $ordenes = OrdenServicio::with('repuestos')->where('mecanico_id', $mecanico->id)->get();

        $solicitudes = collect();

        foreach ($ordenes as $orden) {
            foreach ($orden->repuestos as $repuesto) {
                $solicitudes->push((object)[
                    'fecha'    => $repuesto->pivot->created_at,
                    'nombre'   => $repuesto->nombre,
                    'cantidad' => $repuesto->pivot->cantidad,
                    'orden_id' => $orden->id,
                    // Como el sistema los descuenta de inmediato, el estado automático es Entregado
                    'estado'   => 'Entregado' 
                ]);
            }
        }

        // Ordenamos de más reciente a más antiguo
        $solicitudes = $solicitudes->sortByDesc('fecha')->values();

        // Estadísticas reales
        $entregadosHoy = $solicitudes->where('fecha', '>=', today())->count();
        $enEspera = 0; // En un futuro podrías crear una tabla de "aprobaciones" para usar este
        $rechazados = 0; 

        return view('mecanico_insumos', compact('solicitudes', 'entregadosHoy', 'enEspera', 'rechazados'));
    }
}