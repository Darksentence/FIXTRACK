<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_histories', function (Blueprint $table) {
            $table->id();

            $table->foreignId('service_order_id')
                ->constrained('service_orders')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            // Usuario responsable del cambio.
            // Nullable para no bloquear la integración con autenticación.
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // Estado anterior y nuevo de la orden
            $table->string('previous_status', 50)->nullable();
            $table->string('new_status', 50);

            // Descripción u observación del cambio
            $table->text('notes')->nullable();

            // Momento en que ocurrió el cambio
            $table->timestamp('changed_at')->useCurrent();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_histories');
    }
};