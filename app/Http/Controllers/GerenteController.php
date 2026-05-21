<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Factura;
use App\Models\OrdenServicio;
use App\Models\Empleado;
use App\Models\Repuesto;
use App\Models\Compra;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class GerenteController extends Controller
{
    public function index()
    {
        $mesActual = Carbon::now()->month;
        $inicioMes = Carbon::now()->startOfMonth();

        // KPIs principales
        $ingresosMes = Factura::whereMonth('created_at', $mesActual)->sum('total_facturado');
        
        $ingresosMesBs = \App\Models\Pago::whereMonth('created_at', $mesActual)->get()->sum(function($pago) {
            return $pago->monto * $pago->tasa_bcv;
        });

        $ordenesActivas = OrdenServicio::whereNotIn('estado', ['Finalizado', 'Entregado'])->count();
        $inventarioBajo = Repuesto::whereColumn('stock', '<=', 'stock_minimo')->count();
        $empleadosActivos = Empleado::count();
        $nominaMensual = Empleado::sum('sueldo_base');

        // DATOS REALES: Repuestos usados por mecánicos (de la tabla pivot orden_servicio_repuesto)
        $solicitudesRepuestos = DB::table('orden_servicio_repuesto')
            ->join('repuestos', 'repuestos.id', '=', 'orden_servicio_repuesto.repuesto_id')
            ->join('orden_servicios', 'orden_servicios.id', '=', 'orden_servicio_repuesto.orden_servicio_id')
            ->join('empleados', 'empleados.id', '=', 'orden_servicios.mecanico_id')
            ->select(
                'empleados.nombre as mecanico',
                'orden_servicios.id as orden_id',
                'repuestos.nombre as repuesto',
                'orden_servicio_repuesto.cantidad',
                'orden_servicio_repuesto.created_at'
            )
            ->orderByDesc('orden_servicio_repuesto.created_at')
            ->limit(10)
            ->get();

        // Últimas órdenes activas
        $ultimasOrdenes = OrdenServicio::with(['vehiculo', 'mecanico'])
            ->whereIn('estado', ['En Espera', 'En Reparación'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Pendientes por cobrar
        $pendientesCobrar = Factura::where('estado_pago', '!=', 'Pagado')->sum('saldo_pendiente');

        // Compras pendientes (en tránsito)
        $comprasPendientes = Compra::where('estado', 'En Tránsito')->count();

        return view('gerente', compact(
            'ingresosMes', 'ingresosMesBs', 'ordenesActivas', 'inventarioBajo',
            'empleadosActivos', 'nominaMensual', 'solicitudesRepuestos',
            'ultimasOrdenes', 'pendientesCobrar', 'comprasPendientes'
        ));
    }
}
