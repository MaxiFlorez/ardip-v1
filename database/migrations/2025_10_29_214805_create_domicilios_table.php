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
    Schema::create('domicilios', function (Blueprint $table) {
        $table->id();
        
        // Campos de dirección (todos nullable para flexibilidad)
        $table->string('calle', 255)->nullable();
        $table->string('altura', 20)->nullable();
        $table->string('piso', 10)->nullable();
        $table->string('depto', 10)->nullable();
        $table->string('barrio', 100)->nullable();
        $table->string('localidad', 100)->nullable();
        $table->string('provincia', 100)->nullable()->default('San Juan');
        
        // Coordenadas geográficas (para mapa)
        $table->decimal('latitud', 10, 7)->nullable();
        $table->decimal('longitud', 10, 7)->nullable();
        
        // Campos adicionales opcionales
        $table->string('torre', 10)->nullable();
        $table->string('monoblock', 100)->nullable();
        $table->string('manzana', 20)->nullable();
        $table->string('lote', 20)->nullable();
        $table->string('casa', 20)->nullable();
        $table->string('sector', 100)->nullable();
        
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('domicilios');
    }
};
