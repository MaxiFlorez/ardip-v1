<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('personas', function (Blueprint $table) {
            // Requiere doctrine/dbal para ->change()
            $table->enum('genero', ['masculino', 'femenino', 'otro'])->nullable()->change();
            $table->string('nacionalidad', 50)->nullable()->default('Argentina')->change();
        });
    }

    public function down(): void
    {
        Schema::table('personas', function (Blueprint $table) {
            // Volver a NOT NULL (puede fallar si existen NULLs, manejar en datos antes de rollback)
            $table->enum('genero', ['masculino', 'femenino', 'otro'])->nullable(false)->change();
            $table->string('nacionalidad', 50)->nullable(false)->default('Argentina')->change();
        });
    }
};
