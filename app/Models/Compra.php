<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Compra extends Model {
    protected $fillable = ['numero_orden', 'proveedor_id', 'total', 'monto_iva', 'estado'];
    
    protected $casts = [
        'total' => 'decimal:2',
    ];

    public function proveedor() { return $this->belongsTo(Proveedor::class); }
    public function detalles() { return $this->hasMany(DetalleCompra::class); }
}