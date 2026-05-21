<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Compra;
use App\Models\DetalleCompra;
use App\Models\Proveedor;
use App\Models\Repuesto;
use App\Services\ContabilidadService;
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
        $proveedores = Proveedor::all();

        return view('compras', compact('compras', 'inversionMes', 'pedidosTransito', 'cuentasPorPagar', 'repuestos', 'proveedores'));
    }

    public function guardar(Request $request)
    {
        $request->validate([
            'proveedor_id' => 'required|exists:proveedores,id',
            'repuesto_id' => 'required|exists:repuestos,id',
            'cantidad' => 'required|integer|min:1',
            'costo_unitario' => ['required', 'numeric', 'min:0.01', 'regex:/^\d+(\.\d{1,2})?$/'],
            'estado' => 'required|string'
        ]);

        $subtotal = round($request->cantidad * round((float) $request->costo_unitario, 2), 2);

        // Crear la Orden Principal
        $ultimoRegistro = Compra::latest('id')->first();
        $siguienteId = $ultimoRegistro ? $ultimoRegistro->id + 1 : 1;
        $numeroOrden = 'OC-' . date('Y') . '-' . str_pad($siguienteId, 4, '0', STR_PAD_LEFT);

        $compra = Compra::create([
            'numero_orden' => $numeroOrden,
            'proveedor_id' => $request->proveedor_id,
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

        // Registrar asiento contable de egreso
        ContabilidadService::registrarEgreso(
            $subtotal,
            'Compra inventario: ' . Repuesto::find($request->repuesto_id)->nombre . ' x' . $request->cantidad,
            $compra->numero_orden,
            'compra_inventario',
            Compra::class,
            $compra->id
        );

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

    public function imprimirReporte()
    {
        $compras = Compra::with('proveedor', 'detalles')->orderBy('created_at', 'desc')->get();
        $pdf = \PDF::loadView('pdfs.reporte_compras', compact('compras'));
        $pdf->setPaper('letter', 'portrait');
        return $pdf->stream('reporte_compras.pdf');
    }
}