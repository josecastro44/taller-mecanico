<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Venta;
use App\Models\DetalleVenta;
use App\Models\Repuesto;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class VentaController extends Controller
{
    public function index(Request $request)
    {
        $hoy = Carbon::today();
        
        $ventasDelDia = Venta::whereDate('created_at', $hoy)->sum('total');
        $articulosVendidos = DetalleVenta::whereHas('venta', function($q) use ($hoy) {
            $q->whereDate('created_at', $hoy);
        })->sum('cantidad');
        
        $ticketPromedio = Venta::whereDate('created_at', $hoy)->avg('total') ?? 0;

        $query = Venta::withCount('detalles');
        if ($request->filled('buscar')) {
            $query->where('numero_ticket', 'like', '%' . $request->buscar . '%')
                  ->orWhere('cliente', 'like', '%' . $request->buscar . '%');
        }
        $ventas = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        $repuestos = Repuesto::where('stock', '>', 0)->get();

        return view('ventas', compact('ventas', 'ventasDelDia', 'articulosVendidos', 'ticketPromedio', 'repuestos'));
    }

    public function guardar(Request $request)
    {
        // Validamos los campos nuevos
        $request->validate([
            'cliente'     => 'required|string',
            'cedula'      => 'required|string',
            'telefono'    => 'required|string',
            'repuesto_id' => 'required|exists:repuestos,id',
            'cantidad'    => 'required|integer|min:1',
            'metodo_pago' => 'required|string'
        ]);

        $repuesto = Repuesto::findOrFail($request->repuesto_id);

        if ($request->cantidad > $repuesto->stock) {
            return back()->withErrors(['stock' => 'No hay suficiente stock. Quedan: ' . $repuesto->stock]);
        }

        $precioReal = $repuesto->precio ?? $repuesto->precio_venta ?? 0;

        // 1. Crear el Ticket con los datos del cliente
        $venta = Venta::create([
            'numero_ticket' => 'VT-' . rand(1000, 9999),
            'cliente'       => $request->cliente,
            'cedula'        => $request->cedula,
            'telefono'      => $request->telefono,
            'total'         => $precioReal * $request->cantidad,
            'metodo_pago'   => $request->metodo_pago
        ]);

        // 2. Crear el Detalle
        DetalleVenta::create([
            'venta_id'        => $venta->id,
            'repuesto_id'     => $repuesto->id,
            'cantidad'        => $request->cantidad,
            'precio_unitario' => $precioReal,
            'subtotal'        => $precioReal * $request->cantidad
        ]);

        // 3. Descontar del inventario
        $repuesto->stock -= $request->cantidad;
        $repuesto->save();

        return back()->with('exito', '¡Venta registrada y stock descontado exitosamente!');
    }

    // --- NUEVAS FUNCIONES DE IMPRESIÓN ---

    public function imprimirTicket($id)
    {
        $venta = Venta::with('detalles.repuesto')->findOrFail($id);
        $pdf = Pdf::loadView('pdfs.ticket_venta', compact('venta'));
        // Formato alargado tipo ticket de caja registradora (Impresora térmica)
        $pdf->setPaper([0, 0, 250, 500], 'portrait'); 
        return $pdf->stream('Ticket_'.$venta->numero_ticket.'.pdf');
    }

    public function imprimirReporte()
    {
        $hoy = Carbon::today();
        // Buscamos todas las ventas del mes (o del día, como prefieras, aquí lo puse mensual)
        $ventas = Venta::whereMonth('created_at', Carbon::now()->month)->get();
        $totalVentas = $ventas->sum('total');
        $mes = Carbon::now()->isoFormat('MMMM YYYY');

        $pdf = Pdf::loadView('pdfs.reporte_ventas', compact('ventas', 'totalVentas', 'mes'));
        return $pdf->stream('Reporte_Ventas_Mostrador.pdf');
    }
}