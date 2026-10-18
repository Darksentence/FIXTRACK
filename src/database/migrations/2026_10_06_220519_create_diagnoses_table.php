<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('diagnoses', function (Blueprint $table) {
            $table->id();

            $table->foreignId('service_order_id')
                ->constrained('service_orders')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            // Resultado del diagnóstico técnico
            $table->text('diagnosis');

            // Solución recomendada por el técnico
            $table->text('proposed_solution')->nullable();

            // Observaciones adicionales
            $table->text('observations')->nullable();

            // Fecha en que se realizó el diagnóstico
            $table->timestamp('diagnosed_at')->useCurrent();

            $table->timestamps();

            // Una orden tendrá como máximo un diagnóstico principal
            $table->unique('service_order_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('diagnoses');
    }
};