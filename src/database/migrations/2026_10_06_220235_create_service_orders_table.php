<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_orders', function (Blueprint $table) {
            $table->id();

            // Código visible del ticket, por ejemplo A-0052
            $table->string('order_code', 20)->unique();

            // Relaciones
            $table->foreignId('client_id')
                ->constrained('clients')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignId('equipment_id')
                ->constrained('equipments')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            // Información inicial del servicio
            $table->enum('service_type', ['repair', 'maintenance']);
            $table->text('reported_problem');

            // Estado actual de la orden
            $table->enum('status', [
                'pending_diagnosis',
                'waiting_authorization',
                'authorized',
                'rejected',
                'in_repair',
                'ready_for_pickup',
                'delivered'
            ])->default('pending_diagnosis');

            // Autorización del cliente
            $table->enum('authorization_status', [
                'pending',
                'authorized',
                'rejected'
            ])->default('pending');

            $table->timestamp('authorized_at')->nullable();

            // Costos
            $table->decimal('estimated_cost', 10, 2)->nullable();
            $table->decimal('final_cost', 10, 2)->nullable();

            // Fechas principales
            $table->timestamp('received_at')->useCurrent();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('delivered_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_orders');
    }
};