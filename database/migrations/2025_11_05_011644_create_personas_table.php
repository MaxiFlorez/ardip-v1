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
    Schema::create('personas', function (Blueprint $table) {
        $table->id();
        
        // Campos OBLIGATORIOS
        $table->string('dni', 8)->unique();
        $table->string('nombres', 100);
        $table->string('apellidos', 100);
        $table->date('fecha_nacimiento');
        $table->enum('genero', ['masculino', 'femenino', 'otro']);
        
        // Campos OPCIONALES
        $table->string('alias', 100)->nullable();
        $table->string('nacionalidad', 50)->default('Argentina');
        $table->enum('estado_civil', ['soltero', 'casado', 'divorciado', 'viudo', 'concubinato'])->nullable();
        $table->string('foto', 255)->nullable();
        $table->text('observaciones')->nullable();
        
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personas');
    }
};
