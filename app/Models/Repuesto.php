<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Repuesto extends Model
{
    protected $fillable = [
        'nombre',
        'codigo',
        'marca_vehiculo',
        'modelo_vehiculo',
        'año_vehiculo',
        'marca',
        'categoria',
        'costo_adquisicion',
        'precio_venta',
        'stock',
        'stock_minimo'
    ];

    /**
     * Lógica para autogenerar el código SKU antes de crear el registro
     */
    protected static function booted()
    {
        static::creating(function ($repuesto) {
            // Si el código viene vacío, generamos uno automáticamente
            if (empty($repuesto->codigo)) {
                $count = static::count() + 1;
                // Formato: SKU-2026-00001
                $repuesto->codigo = 'SKU-' . date('Y') . '-' . str_pad($count, 5, '0', STR_PAD_LEFT);
            }
        });
    }
}
