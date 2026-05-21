<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Libro Diario: Registra cronológicamente todos los movimientos financieros.
     * Cada ingreso o egreso (factura, venta, compra, gasto) genera un asiento aquí.
     */
    public function up(): void
    {
        Schema::create('asientos_contables', function (Blueprint $table) {
            $table->id();
            $table->date('fecha');
            $table->string('tipo');          // ingreso | egreso
            $table->string('categoria');     // facturacion, venta_mostrador, compra_inventario, gasto_operativo, nomina
            $table->string('concepto');      // Descripción del movimiento
            $table->string('referencia')->nullable(); // N° factura, ticket, orden de compra
            $table->decimal('monto', 12, 2);
            $table->decimal('saldo_acumulado', 12, 2)->default(0);
            $table->string('origen_tipo')->nullable();  // App\Models\Factura, App\Models\Venta, etc.
            $table->unsignedBigInteger('origen_id')->nullable(); // ID del registro origen
            $table->timestamps();

            $table->index(['fecha', 'tipo']);
            $table->index(['origen_tipo', 'origen_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asientos_contables');
    }
};
