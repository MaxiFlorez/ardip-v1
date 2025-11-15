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
              $table->dropColumn('departamento'); 
            
              // 2. Añadimos la nueva llave foránea
              $table->foreignId('departamento_id')->nullable()->constrained('departamentos');
           });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
           Schema::table('domicilios', function (Blueprint $table) {
              $table->dropForeign(['departamento_id']);
              $table->dropColumn('departamento_id');
              // Re-creamos el campo de texto original
              $table->string('departamento', 100);
           });
    }
};
