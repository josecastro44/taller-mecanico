<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up(): void
    {
        Schema::create('facturas', function (Blueprint $table) {
            $table->id();
            $table->string('numero_factura')->unique();
            $table->string('referencia'); // Para saber si viene de una Orden o Venta
            $table->decimal('subtotal_repuestos', 10, 2)->default(0);
            $table->decimal('subtotal_mano_obra', 10, 2)->default(0);
            $table->decimal('base_imponible', 10, 2)->default(0);
            $table->decimal('monto_iva', 10, 2)->default(0);  // El 16%
            $table->decimal('monto_igtf', 10, 2)->default(0); // El 3%
            $table->decimal('total_facturado', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facturas');
    }
};
