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
        Schema::table('procedimiento_personas', function (Blueprint $table) {
            // Cambiar ENUM a STRING para evitar errores de truncado y permitir valores nuevos
            $table->string('situacion_procesal', 50)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('procedimiento_personas', function (Blueprint $table) {
            // Revertir a un string más amplio si fuera necesario
            $table->string('situacion_procesal', 255)->nullable()->change();
        });
    }
};
