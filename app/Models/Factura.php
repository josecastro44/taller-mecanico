<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
    // Damos permiso a Laravel para guardar en estas columnas
    protected $fillable = [
        'numero_factura',
        'referencia',
        'subtotal_repuestos',
        'subtotal_mano_obra',
        'base_imponible',
        'monto_iva',
        'monto_igtf',
        'total_facturado'
    ];
}