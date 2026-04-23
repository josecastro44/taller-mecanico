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
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('cedula_rut')->unique(); // No pueden haber dos clientes con la misma cédula
            $table->string('telefono');
            $table->string('correo')->nullable(); // nullable significa que es opcional
            $table->timestamps(); // Esto crea automáticamente las columnas "creado_en" y "actualizado_en"
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
