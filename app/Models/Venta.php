<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Venta extends Model 
{
    protected $fillable = ['numero_ticket', 'cliente', 'cedula', 'telefono', 'total', 'metodo_pago'];

public function detalles()
    {
        return $this->hasMany(DetalleVenta::class);
    }
}