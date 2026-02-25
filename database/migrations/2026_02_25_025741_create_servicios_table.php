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
        Schema::create('servicios', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            // Guardamos la categoría (Sencillo, Carga Pesada, Alta Gama)
            $table->string('categoria')->nullable(); 
            // 10 dígitos en total, 2 decimales (Ej: 99999999.99)
            $table->decimal('precio_base', 10, 2); 
            // Un switch de Verdadero/Falso para saber si es un Servicio Especial (precio manual)
            $table->boolean('es_precio_manual')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('servicios');
    }
};
