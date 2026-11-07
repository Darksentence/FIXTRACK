<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_order_attachments', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('service_order_id')
                ->constrained('service_orders')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignId('uploaded_by_user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->string('original_name', 255);
            $table->string('path', 500);
            $table->string('disk', 50)->default('local');
            $table->string('mime_type', 100);
            $table->unsignedBigInteger('size');
            $table->timestamps();

            $table->index(['service_order_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_order_attachments');
    }
};
