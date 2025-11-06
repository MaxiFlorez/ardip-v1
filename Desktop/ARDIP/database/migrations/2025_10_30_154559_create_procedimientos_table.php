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
    Schema::create('procedimientos', function (Blueprint $table) {
        $table->id();
        
        // Identificación
        $table->string('legajo_fiscal', 50);
        $table->text('caratula');
        
        // Fecha y hora
        $table->date('fecha_procedimiento');
        $table->time('hora_procedimiento')->nullable();
        
        // UFI y Brigada
        $table->string('ufi', 100)->default('UFI Delitos contra la Propiedad');
        $table->foreignId('brigada_id')->constrained('brigadas')->onDelete('restrict');
        $table->foreignId('usuario_id')->constrained('users')->onDelete('restrict');
        
        // Órdenes otorgadas
        $table->boolean('orden_allanamiento')->default(true);
        $table->boolean('orden_secuestro')->default(false);
        $table->boolean('orden_detencion')->default(false);
        
        // Resultados
        $table->enum('resultado_secuestro', ['positivo', 'negativo', 'no_aplica'])->default('no_aplica');
        $table->enum('resultado_detencion', ['positivo', 'negativo', 'no_aplica'])->default('no_aplica');
        $table->text('secuestro_detalle')->nullable();
        
        // Observaciones
        $table->text('observaciones')->nullable();
        
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('procedimientos');
    }
};
