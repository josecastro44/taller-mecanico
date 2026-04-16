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
        Schema::table('repuestos', function (Blueprint $table) {
        // Añadimos los campos después del nombre o donde prefieras
        $table->string('marca_vehiculo')->nullable()->after('nombre');
        $table->string('modelo_vehiculo')->nullable()->after('marca_vehiculo');
        $table->string('año_vehiculo')->nullable()->after('modelo_vehiculo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('repuestos', function (Blueprint $table) {
        $table->dropColumn(['marca_vehiculo', 'modelo_vehiculo', 'año_vehiculo']);
        });
    }
};
