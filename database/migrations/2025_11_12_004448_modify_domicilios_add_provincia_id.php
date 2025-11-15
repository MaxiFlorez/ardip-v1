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
        Schema::table('domicilios', function (Blueprint $table) {
            // 1. Eliminamos el viejo campo de texto
            $table->dropColumn('provincia'); 
            
            // 2. Añadimos la nueva llave foránea
            $table->foreignId('provincia_id')->nullable()->constrained('provincias');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('domicilios', function (Blueprint $table) {
            $table->dropForeign(['provincia_id']);
            $table->dropColumn('provincia_id');
            // Re-creamos el campo de texto original
            $table->string('provincia', 100)->default('San Juan');
        });
    }
};
