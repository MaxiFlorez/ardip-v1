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
        // NO-OP: migración obsoleta. Se mantiene por historial pero no modifica la estructura.
        return;
    }

    /**
     * Reverse the migrations.
     * (Esto nos permite deshacer el cambio si algo sale mal)
     */
    public function down(): void
    {
        // NO-OP: migración obsoleta.
        return;
    }
};