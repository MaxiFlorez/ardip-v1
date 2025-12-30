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
        if (!Schema::hasColumn('users', 'brigada_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->foreignId('brigada_id')->nullable()->constrained()->onDelete('set null');
            });
        }

        if (!Schema::hasColumn('procedimientos', 'es_positivo')) {
            Schema::table('procedimientos', function (Blueprint $table) {
                $table->boolean('es_positivo')->default(false)->after('caratula');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('users', 'brigada_id')) {
            Schema::table('users', function (Blueprint $table) {
                // Drop foreign key constraint first
                $table->dropForeign(['brigada_id']);
                $table->dropColumn('brigada_id');
            });
        }

        if (Schema::hasColumn('procedimientos', 'es_positivo')) {
            Schema::table('procedimientos', function (Blueprint $table) {
                $table->dropColumn('es_positivo');
            });
        }
    }
};