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
        // Renombrar observaciones a observacion en persona_domicilio
        Schema::table('persona_domicilio', function (Blueprint $table) {
            if (Schema::hasColumn('persona_domicilio', 'observaciones')) {
                $table->renameColumn('observaciones', 'observacion');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('persona_domicilio', function (Blueprint $table) {
            if (Schema::hasColumn('persona_domicilio', 'observacion')) {
                $table->renameColumn('observacion', 'observaciones');
            }
        });
    }
};
