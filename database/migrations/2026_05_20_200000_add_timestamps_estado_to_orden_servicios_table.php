<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Agrega timestamps por cada etapa del flujo de trabajo.
     * Esto permite calcular duración de cada fase y mostrar el timeline visual.
     */
    public function up(): void
    {
        Schema::table('orden_servicios', function (Blueprint $table) {
            $table->timestamp('fecha_recepcion')->nullable()->after('estado');
            $table->timestamp('fecha_inicio_reparacion')->nullable()->after('fecha_recepcion');
            $table->timestamp('fecha_finalizado')->nullable()->after('fecha_inicio_reparacion');
            $table->timestamp('fecha_entregado')->nullable()->after('fecha_finalizado');
        });
    }

    public function down(): void
    {
        Schema::table('orden_servicios', function (Blueprint $table) {
            $table->dropColumn([
                'fecha_recepcion',
                'fecha_inicio_reparacion',
                'fecha_finalizado',
                'fecha_entregado'
            ]);
        });
    }
};
