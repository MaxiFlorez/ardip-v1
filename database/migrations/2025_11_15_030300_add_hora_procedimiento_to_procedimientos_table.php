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
        if (!Schema::hasColumn('procedimientos', 'hora_procedimiento')) {
            Schema::table('procedimientos', function (Blueprint $table) {
                $table->time('hora_procedimiento')->nullable()->after('fecha_procedimiento');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('procedimientos', 'hora_procedimiento')) {
            Schema::table('procedimientos', function (Blueprint $table) {
                $table->dropColumn('hora_procedimiento');
            });
        }
    }
};
