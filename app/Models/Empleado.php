<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    protected $fillable = [
        'nombre', 
        'cedula', 
        'telefono', 
        'especialidad', 
        'sueldo_base', // <-- Permiso concedido
        'comision'
    ];
}