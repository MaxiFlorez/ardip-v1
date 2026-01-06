<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable(); // Quién realizó la acción
            $table->string('action'); // Acción realizada (create, update, delete, login, etc.)
            $table->string('model_type')->nullable(); // Modelo afectado
            $table->unsignedBigInteger('model_id')->nullable(); // ID del modelo afectado
            $table->text('description')->nullable(); // Descripción legible
            $table->json('properties')->nullable(); // Datos adicionales (cambios, valores previos)
            $table->string('ip_address', 45)->nullable(); // IPv4 o IPv6
            $table->text('user_agent')->nullable(); // Navegador/dispositivo
            $table->string('severity')->default('info'); // info, warning, critical
            $table->timestamps();

            // Índices para búsquedas rápidas
            $table->index(['user_id', 'created_at']);
            $table->index(['model_type', 'model_id']);
            $table->index(['action', 'created_at']);
            $table->index('severity');

            // Relación con users
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('set null'); // Si se elimina el usuario, mantener el log
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
