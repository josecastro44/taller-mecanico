<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Factura;
use App\Models\Pago;
use App\Models\Empleado; 
use App\Models\OrdenServicio;
use App\Services\BcvService;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;


class FinanzasController extends Controller
{
    public function index()
    {
        $facturas = Factura::with('pagos')->orderBy('created_at', 'desc')->paginate(10);
        $inicioMes = Carbon::now()->startOfMonth();

        $ingresosBrutos = Factura::where('created_at', '>=', $inicioMes)->sum('total_facturado');
        $ingresosBrutosBs = Pago::where('created_at', '>=', $inicioMes)->get()->sum(function($p) {
            return $p->monto * $p->tasa_bcv;
        });

        $egresosTotales = Empleado::sum('sueldo_base');
        $utilidadNeta = $ingresosBrutos - $egresosTotales;

        // Nuevas métricas
        $totalPendiente = Factura::where('estado_pago', '!=', 'Pagado')->sum('saldo_pendiente');
        $facturasPendientes = Factura::where('estado_pago', '!=', 'Pagado')->count();

        // Tasa BCV
        $tasaBcv = BcvService::obtenerTasa();

        return view('finanzas', compact(
            'facturas', 'ingresosBrutos', 'ingresosBrutosBs', 'egresosTotales', 'utilidadNeta',
            'totalPendiente', 'facturasPendientes', 'tasaBcv'
        ));
    }

    // NUEVA FUNCIÓN: Calcula todo automáticamente al darle clic en el Monitor
    public function prepararCobro($id)
    {
        $orden = OrdenServicio::with(['servicios', 'repuestos', 'vehiculo'])->findOrFail($id);

        // 1. Sumar Mano de Obra (Servicios)
        $subtotalManoObra = 0;
        foreach($orden->servicios as $servicio) {
            $subtotalManoObra += $servicio->pivot->precio_cobrado ?? $servicio->precio;
        }

        // 2. Sumar Repuestos (Inventario gastado)
        $subtotalRepuestos = 0;
        foreach($orden->repuestos as $repuesto) {
            $subtotalRepuestos += ($repuesto->pivot->cantidad * $repuesto->pivot->precio_unitario);
        }

        // 3. Redirigir a finanzas con los datos listos para el modal
        return redirect('/finanzas')->with([
            'abrir_modal'        => true,
            'orden_id'           => $orden->id,
            'referencia'         => 'O.S. #00' . $orden->id . ' - ' . $orden->vehiculo->placa,
            'subtotal_mano_obra' => $subtotalManoObra,
            'subtotal_repuestos' => $subtotalRepuestos
        ]);
    }

    public function guardar(Request $request)
    {
        $request->validate([
            'referencia'         => 'required|string|max:255',
            'subtotal_repuestos' => 'required|numeric|min:0',
            'subtotal_mano_obra' => 'required|numeric|min:0',
        ]);

        $subtotalRepuestos = $request->subtotal_repuestos;
        $subtotalManoObra  = $request->subtotal_mano_obra;
        $baseImponible     = $subtotalRepuestos + $subtotalManoObra;
        
        $totalFacturado = $baseImponible;

        $ultimoRegistro = Factura::latest('id')->first();
        $siguienteId = $ultimoRegistro ? $ultimoRegistro->id + 1 : 1;
        $numeroFactura = 'FAC-' . str_pad($siguienteId, 5, '0', STR_PAD_LEFT);

        $factura = Factura::create([
            'numero_factura'     => $numeroFactura,
            'referencia'         => $request->referencia,
            'orden_servicio_id'  => $request->orden_id,
            'subtotal_repuestos' => $subtotalRepuestos,
            'subtotal_mano_obra' => $subtotalManoObra,
            'base_imponible'     => $baseImponible,
            'total_facturado'    => $totalFacturado,
            'saldo_pendiente'    => $totalFacturado,
            'estado_pago'        => 'Pendiente'
        ]);

        // FIX MAGNÍFICO: Si la factura viene de una Orden, marcamos el carro como ENTREGADO
        if ($request->filled('orden_id')) {
            $orden = OrdenServicio::find($request->orden_id);
            if ($orden) {
                $orden->estado = 'Entregado';
                $orden->fecha_entregado = now();
                $orden->save();
            }
        }

        return back()->with('exito', '¡Factura ' . $numeroFactura . ' generada! Vehículo marcado como Entregado.');
    }

    /**
     * Registrar un pago/abono a una factura
     */
    public function registrarPago(Request $request)
    {
        $request->validate([
            'factura_id'      => 'required|exists:facturas,id',
            'monto'           => 'required|numeric|min:0.01',
            'metodo_pago'     => 'required|string',
            'referencia_pago' => 'nullable|string|max:100',
        ]);

        $factura = Factura::findOrFail($request->factura_id);

        // Obtener tasa BCV actual
        $tasaBcv = BcvService::obtenerTasa();

        Pago::create([
            'factura_id'      => $factura->id,
            'monto'           => $request->monto,
            'metodo_pago'     => $request->metodo_pago,
            'referencia_pago' => $request->referencia_pago,
            'tasa_bcv'        => $tasaBcv['precio'],
            'observacion'     => $request->observacion,
        ]);

        // Recalcular saldos
        $factura->recalcularSaldo();

        return back()->with('exito', '¡Pago de $' . number_format($request->monto, 2) . ' registrado exitosamente!');
    }

    /**
     * API: Obtener tasa BCV actual (para AJAX)
     */
    public function consultarTasa()
    {
        $tasa = BcvService::obtenerTasa();
        return response()->json($tasa);
    }

    /**
     * API: Refrescar tasa BCV (borra caché)
     */
    public function refrescarTasa()
    {
        $tasa = BcvService::refrescarTasa();
        return response()->json($tasa);
    }

    public function imprimirFactura($id)
    {
        $factura = Factura::with('pagos')->findOrFail($id);
        
        // Aquí podrías buscar también los datos de la Orden si quieres detallar los repuestos
        // Por ahora, generamos la legal con los montos que ya tenemos
        $pdf = Pdf::loadView('pdfs.factura', compact('factura'));
        
        // Esto hace que el PDF se abra en una pestaña nueva
        return $pdf->stream('Factura_'.$factura->numero_factura.'.pdf');
    }

    public function imprimirLibro()
    {
        $inicioMes = Carbon::now()->startOfMonth();
        
        // Buscamos todas las facturas de este mes
        $facturas = Factura::where('created_at', '>=', $inicioMes)->get();
        
        // Calculamos los totales para el pie de página del reporte
        $ingresosBrutos = $facturas->sum('total_facturado');
        $mes = Carbon::now()->isoFormat('MMMM YYYY');

        $pdf = Pdf::loadView('pdfs.libro', compact('facturas', 'ingresosBrutos', 'mes'));
        
        // Orientación horizontal (landscape) porque es una tabla ancha
        $pdf->setPaper('A4', 'landscape');
        
        return $pdf->stream('Libro_Ventas_'.$mes.'.pdf');
    }
}