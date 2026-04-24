<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('ventas', function (Blueprint $table) {
            $table->id();
            $table->string('numero_ticket')->unique();
            $table->string('cliente')->default('Cliente Mostrador');
            $table->decimal('total', 10, 2);
            $table->string('metodo_pago');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('ventas'); }
};