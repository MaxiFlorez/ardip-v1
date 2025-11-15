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
        // Eliminar la tabla pivote persona_domicilio si existe
        Schema::dropIfExists('persona_domicilio');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recrear la tabla pivote en caso de rollback (estructura mínima equivalente)
        Schema::create('persona_domicilio', function (Blueprint $table) {
            $table->id();
            $table->foreignId('persona_id')->constrained('personas')->onDelete('cascade');
            $table->foreignId('domicilio_id')->constrained('domicilios')->onDelete('cascade');
            $table->string('tipo_vinculo')->default('Declarado');
            $table->boolean('es_actual')->default(true);
            $table->text('observaciones')->nullable();
            $table->timestamps();
            $table->unique(['persona_id', 'domicilio_id']);
        });
    }
};
