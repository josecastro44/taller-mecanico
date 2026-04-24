<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrdenServicio extends Model
{
    protected $fillable = [
        'vehiculo_id', 
        'mecanico_id', 
        'diagnostico', 
        'estado', 
        'prioridad'
    ];

    // 1. Conexión con el vehículo
    public function vehiculo() {
        return $this->belongsTo(Vehiculo::class);
    }

    // 2. Conexión con el empleado (Mecánico)
    public function mecanico() {
        return $this->belongsTo(Empleado::class, 'mecanico_id');
    }

    // 3. ¡LA FUNCIÓN QUE FALTABA! Conexión con los servicios a realizar
    public function servicios() {
        return $this->belongsToMany(Servicio::class, 'orden_servicio_servicio')
                    ->withPivot('precio_cobrado');
    }

    // 4. Conexión con los repuestos (Inventario gastado)
    public function repuestos() {
        return $this->belongsToMany(Repuesto::class, 'orden_servicio_repuesto')
                    ->withPivot('cantidad', 'precio_unitario')
                    ->withTimestamps();
    }
}