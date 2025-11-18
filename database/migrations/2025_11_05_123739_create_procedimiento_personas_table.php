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
    Schema::create('procedimiento_personas', function (Blueprint $table) {
        $table->id();
        $table->foreignId('procedimiento_id')->constrained('procedimientos')->onDelete('cascade');
        $table->foreignId('persona_id')->constrained('personas')->onDelete('cascade');
        $table->enum('situacion_procesal', ['DETENIDO', 'APREHENDIDO', 'NOTIFICADO', 'NO HALLADO']);
        $table->boolean('pedido_captura')->default(false);
        $table->text('observaciones')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('procedimiento_personas');
    }
};
