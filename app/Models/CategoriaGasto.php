<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoriaGasto extends Model
{
    protected $table = 'categorias_gasto';

    protected $fillable = ['nombre', 'icono', 'color', 'activa'];

    /**
     * Relación: Una categoría tiene muchos gastos operativos
     */
    public function gastos()
    {
        return $this->hasMany(GastoOperativo::class);
    }

    /**
     * Scope: Solo categorías activas
     */
    public function scopeActivas($query)
    {
        return $query->where('activa', true);
    }
}
