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
    Schema::create('repuestos', function (Blueprint $table) {
        $table->id();
        $table->string('nombre');
        $table->string('codigo')->nullable(); // Para el SKU: REP-001
        $table->string('marca')->nullable();  // Bosch, Castrol, etc.
        $table->string('categoria')->nullable();

        $table->decimal('costo_adquisicion', 10, 2);
        $table->decimal('precio_venta', 10, 2);

        $table->integer('stock')->default(0);
        $table->integer('stock_minimo')->default(5); // Para las alertas de "Stock Crítico"

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('repuestos');
    }
};
