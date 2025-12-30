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
        Schema::table('procedimientos', function (Blueprint $table) {
            if (Schema::hasColumn('procedimientos', 'ufi_interviniente')) {
                $table->dropColumn('ufi_interviniente');
            }
            $table->foreignId('ufi_id')->after('es_positivo')->nullable()->constrained('ufis');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('procedimientos', function (Blueprint $table) {
            $table->dropConstrainedForeignId('ufi_id');
            $table->string('ufi_interviniente')->nullable()->after('es_positivo');
        });
    }
};
