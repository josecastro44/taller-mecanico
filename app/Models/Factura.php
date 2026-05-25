<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
    protected $fillable = [
        'numero_factura',
        'referencia',
        'orden_servicio_id',
        'subtotal_repuestos',
        'subtotal_mano_obra',
        'base_imponible',
        'monto_iva',
        'total_facturado',
        'total_pagado',
        'saldo_pendiente',
        'estado_pago'
    ];

    protected $casts = [
        'subtotal_repuestos' => 'decimal:2',
        'subtotal_mano_obra' => 'decimal:2',
        'base_imponible'     => 'decimal:2',
        'total_facturado'    => 'decimal:2',
        'total_pagado'       => 'decimal:2',
        'saldo_pendiente'    => 'decimal:2',
    ];

    /**
     * Relación: Una factura pertenece a una Orden de Servicio
     */
    public function ordenServicio()
    {
        return $this->belongsTo(\App\Models\OrdenServicio::class);
    }

    /**
     * Relación: Una factura tiene muchos pagos/abonos
     */
    public function pagos()
    {
        return $this->hasMany(\App\Models\Pago::class);
    }

    /**
     * ¿Está completamente pagada?
     */
    public function estaPagada()
    {
        return $this->estado_pago === 'Pagado';
    }

    /**
     * Recalcula los totales de pago basándose en los abonos registrados
     */
    public function recalcularSaldo()
    {
        $totalPagado = $this->pagos()->sum('monto');
        $this->total_pagado = $totalPagado;
        $this->saldo_pendiente = max(0, $this->total_facturado - $totalPagado);

        if ($totalPagado <= 0) {
            $this->estado_pago = 'Pendiente';
        } elseif ($totalPagado >= $this->total_facturado) {
            $this->estado_pago = 'Pagado';
            $this->saldo_pendiente = 0;
        } else {
            $this->estado_pago = 'Parcial';
        }

        $this->save();
    }
}