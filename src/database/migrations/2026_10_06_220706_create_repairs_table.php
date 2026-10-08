<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('repairs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('service_order_id')
                ->constrained('service_orders')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            // Trabajo realizado por el técnico
            $table->text('performed_work');

            // Repuestos o componentes utilizados
            $table->text('parts_used')->nullable();

            // Observaciones finales del técnico
            $table->text('observations')->nullable();

            // Fecha de inicio de la reparación
            $table->timestamp('started_at')->nullable();

            // Fecha de finalización
            $table->timestamp('completed_at')->nullable();

            $table->timestamps();

            // Una reparación principal por orden
            $table->unique('service_order_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('repairs');
    }
};