<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrdenServicio;
use App\Models\Factura;
use App\Models\Empleado;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Vista principal del Dashboard interactivo
     */
    public function index()
    {
        $ordenes = OrdenServicio::with(['vehiculo', 'mecanico', 'servicios'])
            ->orderBy('created_at', 'desc')
            ->get();

        $stats = $this->calcularStats();

        return view('dashboard_taller', compact('ordenes', 'stats'));
    }

    /**
     * API JSON: Devuelve todas las órdenes activas para el polling AJAX
     */
    public function ordenes()
    {
        $ordenes = OrdenServicio::with(['vehiculo', 'mecanico'])
            ->whereIn('estado', ['En Espera', 'En Reparación', 'Finalizado'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($orden) {
                return [
                    'id' => $orden->id,
                    'estado' => $orden->estado,
                    'prioridad' => $orden->prioridad,
                    'diagnostico' => $orden->diagnostico,
                    'vehiculo' => [
                        'placa' => $orden->vehiculo->placa ?? '—',
                        'marca' => $orden->vehiculo->marca ?? '',
                        'modelo' => $orden->vehiculo->modelo ?? '',
                    ],
                    'mecanico' => $orden->mecanico->nombre ?? 'Sin asignar',
                    'tiempo_etapa' => $orden->tiempoEnEtapaActual(),
                    'paso_timeline' => $orden->pasoTimeline(),
                    'fecha_recepcion' => $orden->fecha_recepcion?->toISOString() ?? $orden->created_at->toISOString(),
                    'fecha_inicio' => $orden->fecha_inicio_reparacion?->toISOString(),
                    'fecha_finalizado' => $orden->fecha_finalizado?->toISOString(),
                    'created_at' => $orden->created_at->format('d M, h:i A'),
                ];
            });

        return response()->json($ordenes);
    }

    /**
     * API JSON: Devuelve las estadísticas KPI para actualización en tiempo real
     */
    public function stats()
    {
        return response()->json($this->calcularStats());
    }

    /**
     * API JSON: Devuelve las notificaciones globales del sistema
     */
    public function notificaciones()
    {
        $alertas = [];

        // 1. Alertas de Inventario Bajo
        $repuestosBajos = \App\Models\Repuesto::whereColumn('stock', '<=', 'stock_minimo')->get();
        if ($repuestosBajos->count() > 0) {
            $alertas[] = [
                'tipo' => 'inventario',
                'titulo' => 'Inventario Crítico',
                'mensaje' => 'Hay ' . $repuestosBajos->count() . ' repuesto(s) en su nivel mínimo o agotados.',
                'tiempo' => 'Revisar Almacén'
            ];
        }

        // 2. Órdenes con mucho tiempo en espera (más de 24h)
        $ordenesEstancadas = OrdenServicio::where('estado', 'En Espera')
            ->where('created_at', '<', Carbon::now()->subHours(24))
            ->count();
        if ($ordenesEstancadas > 0) {
            $alertas[] = [
                'tipo' => 'ordenes',
                'titulo' => 'Vehículos en Espera',
                'mensaje' => $ordenesEstancadas . ' vehículo(s) tienen más de 24h esperando revisión.',
                'tiempo' => 'Revisar Monitor'
            ];
        }

        // 3. Facturas pendientes de cobro
        $facturasPendientes = Factura::where('estado_pago', '!=', 'Pagado')->count();
        if ($facturasPendientes > 0) {
            $alertas[] = [
                'tipo' => 'facturas',
                'titulo' => 'Pagos Pendientes',
                'mensaje' => 'Hay ' . $facturasPendientes . ' factura(s) con saldo por cobrar.',
                'tiempo' => 'Revisar Finanzas'
            ];
        }

        return response()->json($alertas);
    }

    /**
     * Calcula todas las métricas KPI del dashboard
     */
    private function calcularStats()
    {
        $hoy = Carbon::today();
        $inicioMes = Carbon::now()->startOfMonth();

        $enEspera = OrdenServicio::where('estado', 'En Espera')->count();
        $enReparacion = OrdenServicio::where('estado', 'En Reparación')->count();
        $finalizadosHoy = OrdenServicio::where('estado', 'Finalizado')
            ->whereDate('updated_at', $hoy)->count();
        $entregadosHoy = OrdenServicio::where('estado', 'Entregado')
            ->whereDate('fecha_entregado', $hoy)->count();

        // Tiempo promedio de reparación (solo órdenes entregadas este mes)
        $ordenesEntregadas = OrdenServicio::whereNotNull('fecha_entregado')
            ->whereNotNull('fecha_recepcion')
            ->where('created_at', '>=', $inicioMes)
            ->get();

        $tiempoPromedio = '—';
        if ($ordenesEntregadas->count() > 0) {
            $totalMinutos = $ordenesEntregadas->sum(function ($orden) {
                return $orden->fecha_recepcion->diffInMinutes($orden->fecha_entregado);
            });
            $promedioMinutos = $totalMinutos / $ordenesEntregadas->count();
            $horas = floor($promedioMinutos / 60);
            $minutos = $promedioMinutos % 60;
            $tiempoPromedio = $horas > 0 ? "{$horas}h {$minutos}min" : "{$minutos}min";
        }

        // Ingresos del mes
        $ingresosMes = Factura::where('created_at', '>=', $inicioMes)->sum('total_facturado');

        // Total órdenes activas
        $totalActivas = $enEspera + $enReparacion + $finalizadosHoy;

        return [
            'en_espera' => $enEspera,
            'en_reparacion' => $enReparacion,
            'finalizados_hoy' => $finalizadosHoy,
            'entregados_hoy' => $entregadosHoy,
            'tiempo_promedio' => $tiempoPromedio,
            'ingresos_mes' => number_format($ingresosMes, 2),
            'total_activas' => $totalActivas,
        ];
    }
}
