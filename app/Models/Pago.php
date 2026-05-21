<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    protected $fillable = [
        'factura_id',
        'monto',
        'metodo_pago',
        'referencia_pago',
        'tasa_bcv',
        'observacion'
    ];

    protected $casts = [
        'monto'    => 'decimal:2',
        'tasa_bcv' => 'decimal:4',
    ];

    /**
     * Relación: Este pago pertenece a una factura
     */
    public function factura()
    {
        return $this->belongsTo(Factura::class);
    }
}
