<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class AsientoContable extends Model
{
    protected $table = 'asientos_contables';

    protected $fillable = [
        'fecha',
        'tipo',         // ingreso | egreso
        'categoria',    // facturacion, venta_mostrador, compra_inventario, gasto_operativo, nomina
        'concepto',
        'referencia',
        'monto',
        'saldo_acumulado',
        'origen_tipo',
        'origen_id'
    ];

    protected $casts = [
        'fecha'            => 'date',
        'monto'            => 'decimal:2',
        'saldo_acumulado'  => 'decimal:2',
    ];

    /**
     * Relación polimórfica: vincula con Factura, Venta, Compra, GastoOperativo, etc.
     */
    public function origen()
    {
        return $this->morphTo('origen', 'origen_tipo', 'origen_id');
    }

    // ===== SCOPES =====

    public function scopeIngresos($query)   { return $query->where('tipo', 'ingreso'); }
    public function scopeEgresos($query)    { return $query->where('tipo', 'egreso'); }

    public function scopePorMes($query, $mes = null, $anio = null)
    {
        $mes  = $mes ?? Carbon::now()->month;
        $anio = $anio ?? Carbon::now()->year;
        return $query->whereMonth('fecha', $mes)->whereYear('fecha', $anio);
    }

    public function scopeEntreFechas($query, $desde, $hasta)
    {
        return $query->whereBetween('fecha', [$desde, $hasta]);
    }

    public function scopePorCategoria($query, $categoria)
    {
        return $query->where('categoria', $categoria);
    }
}
