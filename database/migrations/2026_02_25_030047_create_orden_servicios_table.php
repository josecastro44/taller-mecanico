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
        Schema::create('orden_servicios', function (Blueprint $table) {
            $table->id();
            // Conectamos la orden con el vehículo que se va a reparar
            $table->foreignId('vehiculo_id')->constrained('vehiculos')->onDelete('cascade');
            
            // Datos del diagnóstico y estado
            $table->text('diagnostico')->nullable(); // Puede estar vacío hasta que el mecánico lo revise
            $table->string('prioridad')->default('Normal'); // Alta, Normal, Baja
            
            // El flujo de estados que pidió tu profesora
            $table->enum('estado', [
                'En Espera', 
                'En Reparación', 
                'Esperando Repuestos', 
                'Finalizado', 
                'Entregado'
            ])->default('En Espera');

            // Totales de facturación (se llenarán al final)
            $table->decimal('total_mano_obra', 10, 2)->default(0);
            $table->decimal('total_repuestos', 10, 2)->default(0);
            $table->decimal('total_general', 10, 2)->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orden_servicios');
    }
};
