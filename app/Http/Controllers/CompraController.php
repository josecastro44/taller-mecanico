<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Compra;
use App\Models\DetalleCompra;
use App\Models\Proveedor;
use App\Models\Repuesto;
use Carbon\Carbon;

class CompraController extends Controller
{
    public function index()
    {
        // 1. Matemáticas del Dashboard
        $mesActual = Carbon::now()->month;
        
        $inversionMes = Compra::whereMonth('created_at', $mesActual)->sum('total');
        $pedidosTransito = Compra::where('estado', 'En Tránsito')->count();
        $cuentasPorPagar = Compra::where('estado', 'En Tránsito')->sum('total');

        // 2. Traer Compras y Repuestos
        $compras = Compra::with('proveedor')->orderBy('created_at', 'desc')->paginate(10);
        $repuestos = Repuesto::all(); // Todos los repuestos, para poder comprar

        return view('compras', compact('compras', 'inversionMes', 'pedidosTransito', 'cuentasPorPagar', 'repuestos'));
    }

    public function guardar(Request $request)
    {
        $request->validate([
            'proveedor' => 'required|string',
            'repuesto_id' => 'required|exists:repuestos,id',
            'cantidad' => 'required|integer|min:1',
            'costo_unitario' => 'required|numeric|min:0',
            'estado' => 'required|string'
        ]);

        // Guardar o buscar al proveedor mágicamente
        $proveedor = Proveedor::firstOrCreate(['nombre' => $request->proveedor]);
        $subtotal = $request->cantidad * $request->costo_unitario;

        // Crear la Orden Principal
        $compra = Compra::create([
            'numero_orden' => 'OC-' . date('Y') . '-' . rand(100, 999),
            'proveedor_id' => $proveedor->id,
            'total' => $subtotal,
            'estado' => $request->estado
        ]);

        // Crear el Detalle
        DetalleCompra::create([
            'compra_id' => $compra->id,
            'repuesto_id' => $request->repuesto_id,
            'cantidad' => $request->cantidad,
            'precio_unitario' => $request->costo_unitario,
            'subtotal' => $subtotal
        ]);

        // Si la mercancía ya se recibió, sumarla al inventario
        if ($request->estado === 'Recibido') {
            $repuesto = Repuesto::find($request->repuesto_id);
            $repuesto->stock += $request->cantidad;
            $repuesto->save();
        }

        return back()->with('exito', '¡Orden de Compra registrada exitosamente!');
    }

    public function marcarRecibido($id)
    {
        $compra = Compra::findOrFail($id);
        
        if ($compra->estado === 'En Tránsito') {
            $compra->estado = 'Recibido';
            $compra->save();

            // Sumar al inventario todos los detalles de esta compra
            foreach ($compra->detalles as $detalle) {
                $repuesto = Repuesto::find($detalle->repuesto_id);
                $repuesto->stock += $detalle->cantidad;
                $repuesto->save();
            }
            return back()->with('exito', '¡Mercancía recibida y sumada al inventario!');
        }

        return back();
    }
}