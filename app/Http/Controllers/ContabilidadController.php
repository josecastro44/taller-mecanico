<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AsientoContable;
use App\Models\GastoOperativo;
use App\Models\Empleado;
use App\Services\ContabilidadService;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class ContabilidadController extends Controller
{
    /**
     * Vista principal: Libro Diario
     */
    public function libroDiario(Request $request)
    {
        $desde = $request->desde ? Carbon::parse($request->desde)->startOfDay() : Carbon::now()->startOfMonth();
        $hasta = $request->hasta ? Carbon::parse($request->hasta)->endOfDay() : Carbon::now()->endOfDay();

        $asientos = AsientoContable::entreFechas($desde, $hasta)
            ->orderBy('fecha', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(20)
            ->withQueryString();

        $balance = ContabilidadService::obtenerBalance($desde, $hasta);
        $saldoActual = ContabilidadService::obtenerSaldoActual();
        $desglose = ContabilidadService::desglosePorCategoria($desde, $hasta);

        // KPIs
        $totalAsientos = AsientoContable::entreFechas($desde, $hasta)->count();

        return view('contabilidad', compact(
            'asientos', 'balance', 'saldoActual', 'desglose',
            'totalAsientos', 'desde', 'hasta'
        ));
    }

    /**
     * Vista de Balance General
     */
    public function balance(Request $request)
    {
        $desde = $request->desde ? Carbon::parse($request->desde)->startOfDay() : Carbon::now()->startOfMonth();
        $hasta = $request->hasta ? Carbon::parse($request->hasta)->endOfDay() : Carbon::now()->endOfDay();

        $balance = ContabilidadService::obtenerBalance($desde, $hasta);
        $desglose = ContabilidadService::desglosePorCategoria($desde, $hasta);
        $saldoActual = ContabilidadService::obtenerSaldoActual();

        // Sueldos del mes
        $totalSueldos = Empleado::sum('sueldo_base');

        // Gastos operativos del mes
        $totalGastos = GastoOperativo::where('estado', 'pagado')
            ->where('fecha_pago', '>=', $desde)
            ->where('fecha_pago', '<=', $hasta)
            ->sum('monto');

        return view('contabilidad', compact(
            'balance', 'desglose', 'saldoActual', 'totalSueldos', 'totalGastos',
            'desde', 'hasta'
        ));
    }

    /**
     * Exportar Libro Diario a PDF
     */
    public function exportarLibroPdf(Request $request)
    {
        $desde = $request->desde ? Carbon::parse($request->desde) : Carbon::now()->startOfMonth();
        $hasta = $request->hasta ? Carbon::parse($request->hasta) : Carbon::now();

        $asientos = AsientoContable::entreFechas($desde, $hasta)
            ->orderBy('fecha')
            ->orderBy('id')
            ->get();

        $balance = ContabilidadService::obtenerBalance($desde, $hasta);
        $periodo = $desde->isoFormat('D MMM') . ' - ' . $hasta->isoFormat('D MMM YYYY');

        $pdf = Pdf::loadView('pdfs.libro_diario', compact('asientos', 'balance', 'periodo'));
        $pdf->setPaper('A4', 'landscape');

        return $pdf->stream('Libro_Diario_' . $periodo . '.pdf');
    }

    /**
     * Exportar Gastos Operativos a PDF
     */
    public function exportarGastosPdf(Request $request)
    {
        $desde = $request->desde ? Carbon::parse($request->desde) : Carbon::now()->startOfMonth();
        $hasta = $request->hasta ? Carbon::parse($request->hasta) : Carbon::now();

        $gastos = GastoOperativo::with('categoria')
            ->whereBetween('created_at', [$desde, $hasta])
            ->orderBy('created_at')
            ->get();

        $totalGastos = $gastos->sum('monto');
        $periodo = $desde->isoFormat('D MMM') . ' - ' . $hasta->isoFormat('D MMM YYYY');

        $pdf = Pdf::loadView('pdfs.gastos_operativos', compact('gastos', 'totalGastos', 'periodo'));

        return $pdf->stream('Gastos_Operativos_' . $periodo . '.pdf');
    }

    /**
     * Exportar Balance General a PDF
     */
    public function exportarBalancePdf(Request $request)
    {
        $desde = $request->desde ? Carbon::parse($request->desde) : Carbon::now()->startOfMonth();
        $hasta = $request->hasta ? Carbon::parse($request->hasta) : Carbon::now();

        $balance = ContabilidadService::obtenerBalance($desde, $hasta);
        $desglose = ContabilidadService::desglosePorCategoria($desde, $hasta);
        $periodo = $desde->isoFormat('D MMM') . ' - ' . $hasta->isoFormat('D MMM YYYY');

        $pdf = Pdf::loadView('pdfs.balance_general', compact('balance', 'desglose', 'periodo'));

        return $pdf->stream('Balance_General_' . $periodo . '.pdf');
    }
}
