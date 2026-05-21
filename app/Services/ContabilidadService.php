<?php

namespace App\Services;

use App\Models\AsientoContable;
use Carbon\Carbon;

class ContabilidadService
{
    /**
     * Registra un ingreso en el Libro Diario
     */
    public static function registrarIngreso($monto, $concepto, $referencia = null, $categoria = 'facturacion', $origenTipo = null, $origenId = null)
    {
        return self::crearAsiento('ingreso', $monto, $concepto, $referencia, $categoria, $origenTipo, $origenId);
    }

    /**
     * Registra un egreso en el Libro Diario
     */
    public static function registrarEgreso($monto, $concepto, $referencia = null, $categoria = 'gasto_operativo', $origenTipo = null, $origenId = null)
    {
        return self::crearAsiento('egreso', $monto, $concepto, $referencia, $categoria, $origenTipo, $origenId);
    }

    /**
     * Crea el asiento contable y calcula el saldo acumulado
     */
    private static function crearAsiento($tipo, $monto, $concepto, $referencia, $categoria, $origenTipo, $origenId)
    {
        // Obtener el saldo acumulado del último asiento
        $ultimoAsiento = AsientoContable::orderBy('id', 'desc')->first();
        $saldoAnterior = $ultimoAsiento ? (float) $ultimoAsiento->saldo_acumulado : 0;

        // Calcular nuevo saldo
        $nuevoSaldo = $tipo === 'ingreso' 
            ? round($saldoAnterior + $monto, 2) 
            : round($saldoAnterior - $monto, 2);

        return AsientoContable::create([
            'fecha'            => Carbon::today(),
            'tipo'             => $tipo,
            'categoria'        => $categoria,
            'concepto'         => $concepto,
            'referencia'       => $referencia,
            'monto'            => round($monto, 2),
            'saldo_acumulado'  => $nuevoSaldo,
            'origen_tipo'      => $origenTipo,
            'origen_id'        => $origenId,
        ]);
    }

    /**
     * Obtener balance entre dos fechas
     */
    public static function obtenerBalance($desde = null, $hasta = null)
    {
        $desde = $desde ?? Carbon::now()->startOfMonth();
        $hasta = $hasta ?? Carbon::now()->endOfDay();

        $ingresos = AsientoContable::ingresos()->entreFechas($desde, $hasta)->sum('monto');
        $egresos  = AsientoContable::egresos()->entreFechas($desde, $hasta)->sum('monto');

        return [
            'ingresos'      => round($ingresos, 2),
            'egresos'       => round($egresos, 2),
            'utilidad_neta' => round($ingresos - $egresos, 2),
        ];
    }

    /**
     * Obtener el saldo acumulado actual
     */
    public static function obtenerSaldoActual()
    {
        $ultimo = AsientoContable::orderBy('id', 'desc')->first();
        return $ultimo ? (float) $ultimo->saldo_acumulado : 0;
    }

    /**
     * Obtener desglose de ingresos/egresos por categoría
     */
    public static function desglosePorCategoria($desde = null, $hasta = null)
    {
        $desde = $desde ?? Carbon::now()->startOfMonth();
        $hasta = $hasta ?? Carbon::now()->endOfDay();

        return AsientoContable::entreFechas($desde, $hasta)
            ->selectRaw('tipo, categoria, SUM(monto) as total')
            ->groupBy('tipo', 'categoria')
            ->get();
    }
}
