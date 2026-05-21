<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Vincula cada factura con su orden de servicio de origen.
     * Permite trazabilidad completa: Recepción → Mecánico → Monitor → Factura → Pago
     */
    public function up(): void
    {
        Schema::table('facturas', function (Blueprint $table) {
            $table->foreignId('orden_servicio_id')->nullable()->after('referencia')
                  ->constrained('orden_servicios')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('facturas', function (Blueprint $table) {
            $table->dropForeign(['orden_servicio_id']);
            $table->dropColumn('orden_servicio_id');
        });
    }
};
