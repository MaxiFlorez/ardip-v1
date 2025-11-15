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
        Schema::table('users', function (Blueprint $table) {
            // 1) Columna 'brigada_id' opcional y con FK a 'brigadas'
            // 2) after('password') para ubicarla lógicamente
            if (!Schema::hasColumn('users', 'brigada_id')) {
                $table->foreignId('brigada_id')->nullable()->constrained('brigadas')->after('password');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'brigada_id')) {
                $table->dropForeign(['brigada_id']);
                $table->dropColumn('brigada_id');
            }
        });
    }
};
