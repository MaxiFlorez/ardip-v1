<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Elimina la columna 'alias' de la tabla 'personas'
        Schema::table('personas', function (Blueprint $table) {
            $table->dropColumn('alias');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Vuelve a agregar la columna 'alias' si se revierte la migración
        Schema::table('personas', function (Blueprint $table) {
            $table->string('alias')->nullable();
        });
    }
};
