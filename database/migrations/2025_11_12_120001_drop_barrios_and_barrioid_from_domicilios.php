<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Reverse barrio integration: drop barrios table and barrio_id FK, restore barrio text column.
     */
    public function up(): void
    {
        // NO-OP: migración de limpieza obsoleta.
        return; 
    }

    /**
     * Attempt to recreate previous state (minimal).
     */
    public function down(): void
    {
        // NO-OP
        return;
    }
};
