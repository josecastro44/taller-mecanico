<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleVenta extends Model
{
    // Permiso para guardar datos en estas columnas
    protected $fillable = [
        'venta_id', 
        'repuesto_id', 
        'cantidad', 
        'precio_unitario', 
        'subtotal'
    ];

    // LA SOLUCIÓN: Le decimos a Laravel que este detalle pertenece a un Repuesto
    public function repuesto()
    {
        return $this->belongsTo(Repuesto::class);
    }

    // Y también le decimos que pertenece a una Venta principal
    public function venta()
    {
        return $this->belongsTo(Venta::class);
    }
}