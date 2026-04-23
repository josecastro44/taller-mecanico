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
        Schema::create('vehiculos', function (Blueprint $table) {
            $table->id();
            // Esta línea conecta el vehículo con la tabla clientes
            $table->foreignId('cliente_id')->constrained('clientes')->onDelete('cascade');
            
            // Los datos de la Hoja de Vida del Vehículo
            $table->string('placa')->unique(); // La placa no se puede repetir
            $table->string('marca');
            $table->string('modelo');
            $table->integer('anio');
            $table->integer('kilometraje');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehiculos');
    }
};
