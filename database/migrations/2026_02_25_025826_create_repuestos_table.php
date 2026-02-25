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
            // Lo que le cuesta al taller comprarlo
            $table->decimal('costo_adquisicion', 10, 2);
            // En cuánto se lo venden al cliente
            $table->decimal('precio_venta', 10, 2);
            // La cantidad de piezas que tienen físicamente en el taller
            $table->integer('stock')->default(0); 
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
