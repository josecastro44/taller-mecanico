<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class DetalleCompra extends Model {
    protected $fillable = ['compra_id', 'repuesto_id', 'cantidad', 'precio_unitario', 'subtotal'];
    
    public function compra() { return $this->belongsTo(Compra::class); }
    public function repuesto() { return $this->belongsTo(Repuesto::class); }
}