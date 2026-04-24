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
        Schema::create('empleados', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('cedula')->unique();
            $table->string('telefono')->nullable();
            $table->string('especialidad');
            $table->decimal('sueldo_base', 10, 2)->default(0); // <-- NUEVO: El sueldo fijo
            $table->decimal('comision', 5, 2)->default(0);     // <-- El % de comisión
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empleados');
    }
};
