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
        'prioridad',
        'fecha_recepcion',
        'fecha_inicio_reparacion',
        'fecha_finalizado',
        'fecha_entregado'
    ];

    protected $casts = [
        'fecha_recepcion' => 'datetime',
        'fecha_inicio_reparacion' => 'datetime',
        'fecha_finalizado' => 'datetime',
        'fecha_entregado' => 'datetime',
    ];

    /**
     * Calcula el tiempo transcurrido en la etapa actual
     */
    public function tiempoEnEtapaActual()
    {
        $inicio = match($this->estado) {
            'En Espera' => $this->fecha_recepcion ?? $this->created_at,
            'En Reparación' => $this->fecha_inicio_reparacion,
            'Finalizado' => $this->fecha_finalizado,
            'Entregado' => $this->fecha_entregado,
            default => $this->created_at,
        };

        if (!$inicio) return '—';

        $diff = $inicio->diff(now());
        if ($diff->days > 0) return $diff->days . 'd ' . $diff->h . 'h';
        if ($diff->h > 0) return $diff->h . 'h ' . $diff->i . 'min';
        return $diff->i . 'min';
    }

    /**
     * Retorna el paso actual del timeline (1-4)
     */
    public function pasoTimeline()
    {
        return match($this->estado) {
            'En Espera' => 1,
            'En Reparación' => 2,
            'Finalizado' => 3,
            'Entregado' => 4,
            default => 1,
        };
    }

    /**
     * Calcula el tiempo total del servicio (recepción hasta entrega)
     */
    public function tiempoTotal()
    {
        if (!$this->fecha_entregado) return null;
        $inicio = $this->fecha_recepcion ?? $this->created_at;
        return $inicio->diff($this->fecha_entregado);
    }

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

    // 5. Conexión con la Factura generada
    public function factura() {
        return $this->hasOne(Factura::class, 'orden_servicio_id');
    }
}