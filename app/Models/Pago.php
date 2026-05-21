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

    /**
     * Relación: Este pago pertenece a una factura
     */
    public function factura()
    {
        return $this->belongsTo(Factura::class);
    }
}
