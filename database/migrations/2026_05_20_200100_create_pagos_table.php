<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabla para registrar pagos/abonos parciales o totales contra facturas.
     * Permite manejar pagos fraccionados y rastrear la tasa BCV al momento del pago.
     */
    public function up(): void
    {
        Schema::create('pagos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('factura_id')->constrained('facturas')->onDelete('cascade');
            $table->decimal('monto', 10, 2);
            $table->string('metodo_pago'); // Efectivo, Transferencia, Divisas, Pago Móvil, Punto de Venta
            $table->string('referencia_pago')->nullable(); // Número de referencia bancaria
            $table->decimal('tasa_bcv', 10, 4)->nullable(); // Tasa al momento del pago
            $table->text('observacion')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};
