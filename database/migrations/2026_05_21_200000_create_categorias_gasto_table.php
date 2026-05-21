<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categorias_gasto', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');        // Agua, Electricidad, Gas, Internet, etc.
            $table->string('icono')->default('ph-receipt');  // Icono Phosphor
            $table->string('color')->default('#728495');     // Color del badge
            $table->boolean('activa')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categorias_gasto');
    }
};
