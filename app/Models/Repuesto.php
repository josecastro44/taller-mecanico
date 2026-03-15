<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Repuesto extends Model
{
    // Esto permite que el controlador guarde los datos masivamente
    protected $fillable = [
        'nombre',
        'costo_adquisicion',
        'precio_venta',
        'stock'
    ];
}
