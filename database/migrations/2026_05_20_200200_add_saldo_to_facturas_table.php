<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Agrega campos de control de saldo a las facturas.
     * Permite manejar pagos parciales y ver estado de cobro.
     */
    public function up(): void
    {
        Schema::table('facturas', function (Blueprint $table) {
            $table->decimal('total_pagado', 10, 2)->default(0)->after('total_facturado');
            $table->decimal('saldo_pendiente', 10, 2)->default(0)->after('total_pagado');
            $table->string('estado_pago', 20)->default('Pendiente')->after('saldo_pendiente');
            // Pendiente, Parcial, Pagado
        });
    }

    public function down(): void
    {
        Schema::table('facturas', function (Blueprint $table) {
            $table->dropColumn(['total_pagado', 'saldo_pendiente', 'estado_pago']);
        });
    }
};
