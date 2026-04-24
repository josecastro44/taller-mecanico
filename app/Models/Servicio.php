<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    protected $fillable = [
        'codigo', 
        'descripcion', 
        'precio_sencillo', 
        'precio_alta_gama', 
        'precio_carga_pesada', 
        'categoria'
    ];
}