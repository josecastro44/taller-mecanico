<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class GastoOperativo extends Model
{
    protected $table = 'gastos_operativos';

    protected $fillable = [
        'categoria_gasto_id',
        'descripcion',
        'monto',
        'fecha_pago',
        'frecuencia',
        'prox_vencimiento',
        'estado',
        'metodo_pago',
        'referencia_pago',
        'observaciones'
    ];

    protected $casts = [
        'monto'             => 'decimal:2',
        'fecha_pago'        => 'date',
        'prox_vencimiento'  => 'date',
    ];

    /**
     * Relación: Pertenece a una categoría
     */
    public function categoria()
    {
        return $this->belongsTo(CategoriaGasto::class, 'categoria_gasto_id');
    }

    /**
     * ¿Está vencido?
     */
    public function estaVencido(): bool
    {
        return $this->prox_vencimiento && $this->prox_vencimiento->isPast() && $this->estado !== 'pagado';
    }

    /**
     * ¿Está próximo a vencer? (dentro de 5 días)
     */
    public function proximoAVencer(): bool
    {
        if (!$this->prox_vencimiento || $this->estado === 'pagado') return false;
        return $this->prox_vencimiento->between(Carbon::today(), Carbon::today()->addDays(5));
    }

    /**
     * Calcula la próxima fecha de vencimiento basándose en la frecuencia
     */
    public function calcularProximoVencimiento(): ?Carbon
    {
        if (!$this->prox_vencimiento) return null;

        return match($this->frecuencia) {
            'semanal'    => $this->prox_vencimiento->addWeek(),
            'quincenal'  => $this->prox_vencimiento->addDays(15),
            'mensual'    => $this->prox_vencimiento->addMonth(),
            'unico'      => null,
            default      => $this->prox_vencimiento->addMonth(),
        };
    }

    /**
     * Scopes útiles
     */
    public function scopePendientes($query) { return $query->where('estado', 'pendiente'); }
    public function scopePagados($query) { return $query->where('estado', 'pagado'); }
    public function scopeVencidos($query) { return $query->where('estado', 'pendiente')->where('prox_vencimiento', '<', Carbon::today()); }
    public function scopeDelMes($query) { return $query->whereMonth('created_at', Carbon::now()->month)->whereYear('created_at', Carbon::now()->year); }
}
