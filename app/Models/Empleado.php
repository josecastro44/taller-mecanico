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
        'sueldo_base',
        'comision',
        'user_id'
    ];

    protected $casts = [
        'sueldo_base' => 'decimal:2',
        'comision' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}