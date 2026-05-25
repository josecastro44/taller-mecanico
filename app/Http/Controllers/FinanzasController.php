<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Factura;
use App\Models\Pago;
use App\Models\Empleado;
use App\Models\GastoOperativo;
use App\Models\OrdenServicio;
use App\Services\BcvService;
use App\Services\ContabilidadService;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;


class FinanzasController extends Controller
{
    public function index()
    {
        $facturas = Factura::with(['pagos', 'ordenServicio.vehiculo.cliente'])->orderBy('created_at', 'desc')->paginate(10);
        $inicioMes = Carbon::now()->startOfMonth();

        // Ventas de mostrador del mes
        $ventasMostrador = \App\Models\Venta::where('created_at', '>=', $inicioMes)->sum('total');

        $ingresosBrutos = Factura::where('created_at', '>=', $inicioMes)->sum('total_facturado') + $ventasMostrador;
        $ingresosBrutosBs = Pago::where('created_at', '>=', $inicioMes)->get()->sum(function($p) {
            return $p->monto * $p->tasa_bcv;
        });

        $egresosSueldos = Empleado::sum('sueldo_base'); // Asumiendo nómina mensual fija por ahora
        $egresosGastos = GastoOperativo::where('estado', 'pagado')->where('fecha_pago', '>=', $inicioMes)->sum('monto');
        $egresosTotales = round($egresosSueldos + $egresosGastos, 2);
        $utilidadNeta = round($ingresosBrutos - $egresosTotales, 2);

        // Nuevas métricas
        $totalPendiente = Factura::where('estado_pago', '!=', 'Pagado')->sum('saldo_pendiente');
        $facturasPendientes = Factura::where('estado_pago', '!=', 'Pagado')->count();

        // Tasa BCV
        $tasaBcv = BcvService::obtenerTasa();

        return view('finanzas', compact(
            'facturas', 'ingresosBrutos', 'ingresosBrutosBs', 'egresosTotales', 'utilidadNeta',
            'totalPendiente', 'facturasPendientes', 'tasaBcv', 'ventasMostrador'
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
            'subtotal_repuestos' => ['required', 'numeric', 'min:0', 'regex:/^\d+(\.\d{1,2})?$/'],
            'subtotal_mano_obra' => ['required', 'numeric', 'min:0', 'regex:/^\d+(\.\d{1,2})?$/'],
            'porcentaje_iva'     => 'nullable|numeric|min:0'
        ]);

        $subtotalRepuestos = round((float) $request->subtotal_repuestos, 2);
        $subtotalManoObra  = round((float) $request->subtotal_mano_obra, 2);
        $baseImponible     = round($subtotalRepuestos + $subtotalManoObra, 2);
        
        $porcentaje_iva = $request->filled('porcentaje_iva') ? (float)$request->porcentaje_iva : 16;
        $monto_iva = round($baseImponible * ($porcentaje_iva / 100), 2);
        
        $totalFacturado = $baseImponible + $monto_iva;

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
            'monto_iva'          => $monto_iva,
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

        // Registrar asiento contable de ingreso automáticamente
        ContabilidadService::registrarIngreso(
            $totalFacturado,
            'Factura emitida: ' . $numeroFactura . ' — ' . $request->referencia,
            $numeroFactura,
            'facturacion',
            Factura::class,
            $factura->id
        );

        return back()->with('exito', '¡Factura ' . $numeroFactura . ' generada! Vehículo marcado como Entregado.');
    }

    /**
     * Registrar un pago/abono a una factura
     */
    public function registrarPago(Request $request)
    {
        $request->validate([
            'factura_id'      => 'required|exists:facturas,id',
            'monto'           => ['required', 'numeric', 'min:0.01', 'regex:/^\d+(\.\d{1,2})?$/'],
            'metodo_pago'     => 'required|string',
            'referencia_pago' => 'nullable|string|max:100',
        ]);

        // Validar que el monto no exceda el saldo pendiente
        $factura = Factura::findOrFail($request->factura_id);
        $saldoPendiente = $factura->saldo_pendiente > 0 ? $factura->saldo_pendiente : $factura->total_facturado;
        if (round((float) $request->monto, 2) > round($saldoPendiente, 2)) {
            return back()->withErrors(['monto' => 'El monto ($' . number_format($request->monto, 2) . ') excede el saldo pendiente ($' . number_format($saldoPendiente, 2) . ').']);
        }

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
        $factura = Factura::with(['pagos', 'ordenServicio.servicios', 'ordenServicio.repuestos', 'ordenServicio.vehiculo'])->findOrFail($id);
        $tasaBcv = BcvService::obtenerTasa()['precio'];
        
        $pdf = Pdf::loadView('pdfs.factura', compact('factura', 'tasaBcv'));
        
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