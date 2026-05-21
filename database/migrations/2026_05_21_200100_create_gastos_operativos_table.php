<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gastos_operativos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('categoria_gasto_id')->constrained('categorias_gasto')->onDelete('cascade');
            $table->string('descripcion');
            $table->decimal('monto', 10, 2);
            $table->date('fecha_pago')->nullable();          // Fecha en que se pagó
            $table->string('frecuencia')->default('mensual'); // mensual, quincenal, semanal, unico
            $table->date('prox_vencimiento')->nullable();     // Próxima fecha de pago
            $table->string('estado')->default('pendiente');   // pendiente, pagado, vencido
            $table->string('metodo_pago')->nullable();
            $table->string('referencia_pago')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gastos_operativos');
    }
};
