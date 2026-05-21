<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrdenServicio;
use App\Models\Factura;
use App\Models\Venta;
use App\Models\DetalleVenta;
use App\Models\Empleado;
use App\Models\Repuesto;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class ReporteController extends Controller
{
    /**
     * Vista principal de reportes con datos reales de la BD
     */
    public function index(Request $request)
    {
        // Filtros de fecha
        $desde = $request->desde ? Carbon::parse($request->desde)->startOfDay() : Carbon::now()->startOfMonth();
        $hasta = $request->hasta ? Carbon::parse($request->hasta)->endOfDay() : Carbon::now()->endOfDay();
        $anio = Carbon::now()->year;

        // =============================================
        // 1. Carros atendidos por mes (últimos 6 meses)
        // =============================================
        $carrosPorMes = OrdenServicio::selectRaw('MONTH(created_at) as mes, COUNT(*) as total')
            ->whereYear('created_at', $anio)
            ->groupByRaw('MONTH(created_at)')
            ->orderByRaw('MONTH(created_at)')
            ->pluck('total', 'mes');

        $carrosLabels = [];
        $carrosData = [];
        for ($m = 1; $m <= 12; $m++) {
            $carrosLabels[] = Carbon::create()->month($m)->isoFormat('MMM');
            $carrosData[] = $carrosPorMes[$m] ?? 0;
        }

        // =============================================
        // 2. Ventas de repuestos por mes
        // =============================================
        $ventasPorMes = Venta::selectRaw('MONTH(created_at) as mes, SUM(total) as total')
            ->whereYear('created_at', $anio)
            ->groupByRaw('MONTH(created_at)')
            ->orderByRaw('MONTH(created_at)')
            ->pluck('total', 'mes');

        $ventasData = [];
        for ($m = 1; $m <= 12; $m++) {
            $ventasData[] = (float) ($ventasPorMes[$m] ?? 0);
        }

        // =============================================
        // 3. Tipos de servicio (distribución)
        // =============================================
        $tiposServicio = DB::table('orden_servicio_servicio')
            ->join('servicios', 'servicios.id', '=', 'orden_servicio_servicio.servicio_id')
            ->selectRaw('servicios.descripcion, COUNT(*) as total')
            ->groupBy('servicios.descripcion')
            ->orderByDesc('total')
            ->limit(8)
            ->get();

        $tiposLabels = $tiposServicio->pluck('descripcion')->toArray();
        $tiposData = $tiposServicio->pluck('total')->toArray();

        // =============================================
        // 4. Balance financiero (ingresos por mes)
        // =============================================
        $ingresosFacturas = Factura::selectRaw('MONTH(created_at) as mes, SUM(total_facturado) as total')
            ->whereYear('created_at', $anio)
            ->groupByRaw('MONTH(created_at)')
            ->pluck('total', 'mes');

        $ingresosVentas = Venta::selectRaw('MONTH(created_at) as mes, SUM(total) as total')
            ->whereYear('created_at', $anio)
            ->groupByRaw('MONTH(created_at)')
            ->pluck('total', 'mes');

        $egresosPorMes = \App\Models\AsientoContable::egresos()
            ->selectRaw('MONTH(fecha) as mes, SUM(monto) as total')
            ->whereYear('fecha', $anio)
            ->groupByRaw('MONTH(fecha)')
            ->pluck('total', 'mes');

        $ingresosData = [];
        $egresosData = [];
        for ($m = 1; $m <= 12; $m++) {
            $ingresoF = (float) ($ingresosFacturas[$m] ?? 0);
            $ingresoV = (float) ($ingresosVentas[$m] ?? 0);
            $ingresosData[] = $ingresoF + $ingresoV;
            $egresosData[] = (float) ($egresosPorMes[$m] ?? 0);
        }

        // =============================================
        // 5. Rendimiento por mecánico
        // =============================================
        $mecanicos = OrdenServicio::with('mecanico')
            ->whereIn('estado', ['Finalizado', 'Entregado'])
            ->whereBetween('created_at', [$desde, $hasta])
            ->get()
            ->groupBy('mecanico_id');

        $mecanicosLabels = [];
        $mecanicosData = [];
        foreach ($mecanicos as $mecanicoId => $ordenes) {
            $nombre = $ordenes->first()->mecanico->nombre ?? 'Sin asignar';
            $mecanicosLabels[] = $nombre;
            $mecanicosData[] = $ordenes->count();
        }

        // =============================================
        // 6. Estado actual de órdenes
        // =============================================
        $estadoOrdenes = OrdenServicio::selectRaw("estado, COUNT(*) as total")
            ->groupBy('estado')
            ->pluck('total', 'estado');

        $estadoLabels = $estadoOrdenes->keys()->toArray();
        $estadoData = $estadoOrdenes->values()->toArray();

        // =============================================
        // KPIs para las tarjetas superiores
        // =============================================
        $inicioMes = Carbon::now()->startOfMonth();
        $kpis = [
            'ingresos_mes' => Factura::where('created_at', '>=', $inicioMes)->sum('total_facturado'),
            'ordenes_mes' => OrdenServicio::where('created_at', '>=', $inicioMes)->count(),
            'ordenes_completadas' => OrdenServicio::whereIn('estado', ['Finalizado', 'Entregado'])
                ->whereBetween('created_at', [$desde, $hasta])->count(),
            'ticket_promedio' => Factura::where('created_at', '>=', $inicioMes)->avg('total_facturado') ?? 0,
            'repuestos_vendidos' => DetalleVenta::whereHas('venta', function ($q) use ($inicioMes) {
                $q->where('created_at', '>=', $inicioMes);
            })->sum('cantidad'),
            'inventario_bajo' => Repuesto::whereColumn('stock', '<=', 'stock_minimo')->count(),
        ];

        // Tiempo promedio de reparación
        $ordenesConTiempo = OrdenServicio::whereNotNull('fecha_entregado')
            ->whereNotNull('fecha_recepcion')
            ->whereBetween('created_at', [$desde, $hasta])
            ->get();

        if ($ordenesConTiempo->count() > 0) {
            $totalMin = $ordenesConTiempo->sum(fn($o) => $o->fecha_recepcion->diffInMinutes($o->fecha_entregado));
            $promMin = $totalMin / $ordenesConTiempo->count();
            $kpis['tiempo_promedio'] = floor($promMin / 60) . 'h ' . ($promMin % 60) . 'min';
        } else {
            $kpis['tiempo_promedio'] = '—';
        }

        // =============================================
        // Rentabilidad por servicio
        // =============================================
        $rentabilidadServicios = DB::table('orden_servicio_servicio')
            ->join('servicios', 'servicios.id', '=', 'orden_servicio_servicio.servicio_id')
            ->selectRaw('servicios.descripcion as nombre, COUNT(*) as cantidad, SUM(orden_servicio_servicio.precio_cobrado) as ingresos')
            ->groupBy('servicios.descripcion')
            ->orderByDesc('ingresos')
            ->get();

        return view('reportes', compact(
            'carrosLabels', 'carrosData',
            'ventasData',
            'tiposLabels', 'tiposData',
            'ingresosData', 'egresosData',
            'mecanicosLabels', 'mecanicosData',
            'estadoLabels', 'estadoData',
            'kpis', 'rentabilidadServicios',
            'desde', 'hasta', 'carrosLabels'
        ));
    }

    /**
     * Exportar reporte a PDF
     */
    public function exportarPdf(Request $request)
    {
        $desde = $request->desde ? Carbon::parse($request->desde) : Carbon::now()->startOfMonth();
        $hasta = $request->hasta ? Carbon::parse($request->hasta) : Carbon::now();

        $ordenes = OrdenServicio::with(['vehiculo', 'mecanico', 'servicios'])
            ->whereBetween('created_at', [$desde, $hasta])
            ->orderBy('created_at', 'desc')
            ->get();

        $facturas = Factura::whereBetween('created_at', [$desde, $hasta])->get();
        $totalIngresos = $facturas->sum('total_facturado');
        $totalOrdenes = $ordenes->count();
        $mes = $desde->isoFormat('D MMM') . ' - ' . $hasta->isoFormat('D MMM YYYY');

        $pdf = Pdf::loadView('pdfs.reporte_general', compact('ordenes', 'facturas', 'totalIngresos', 'totalOrdenes', 'mes'));
        $pdf->setPaper('A4', 'landscape');

        return $pdf->stream('Reporte_General_' . $mes . '.pdf');
    }

    /**
     * Exportar reporte a CSV
     */
    public function exportarCsv(Request $request)
    {
        $desde = $request->desde ? Carbon::parse($request->desde) : Carbon::now()->startOfMonth();
        $hasta = $request->hasta ? Carbon::parse($request->hasta) : Carbon::now();

        $ordenes = OrdenServicio::with(['vehiculo', 'mecanico'])
            ->whereBetween('created_at', [$desde, $hasta])
            ->orderBy('created_at', 'desc')
            ->get();

        $filename = 'reporte_taller_' . now()->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($ordenes) {
            $file = fopen('php://output', 'w');
            // BOM para Excel
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($file, ['#O.S.', 'Fecha', 'Placa', 'Vehículo', 'Mecánico', 'Estado', 'Diagnóstico']);

            foreach ($ordenes as $orden) {
                fputcsv($file, [
                    '#00' . $orden->id,
                    $orden->created_at->format('d/m/Y H:i'),
                    $orden->vehiculo->placa ?? '',
                    ($orden->vehiculo->marca ?? '') . ' ' . ($orden->vehiculo->modelo ?? ''),
                    $orden->mecanico->nombre ?? 'Sin asignar',
                    $orden->estado,
                    $orden->diagnostico
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
