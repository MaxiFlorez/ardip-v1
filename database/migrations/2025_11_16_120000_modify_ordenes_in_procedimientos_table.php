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
		// Paso 1: agregar nueva columna orden_judicial
		Schema::table('procedimientos', function (Blueprint $table) {
			if (!Schema::hasColumn('procedimientos', 'orden_judicial')) {
				$table->string('orden_judicial')->nullable()->after('usuario_id');
			}
		});

		// Paso 2: eliminar columnas antiguas en bloque separado
		Schema::table('procedimientos', function (Blueprint $table) {
			$cols = ['orden_allanamiento', 'orden_secuestro', 'orden_detencion'];
			$existing = array_filter($cols, fn($c) => Schema::hasColumn('procedimientos', $c));
			if (!empty($existing)) {
				$table->dropColumn($existing);
			}
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::table('procedimientos', function (Blueprint $table) {
			// Restaurar columnas booleanas si no existen
			if (!Schema::hasColumn('procedimientos', 'orden_allanamiento')) {
				$table->boolean('orden_allanamiento')->default(true)->after('usuario_id');
			}
			if (!Schema::hasColumn('procedimientos', 'orden_secuestro')) {
				$table->boolean('orden_secuestro')->default(false)->after('orden_allanamiento');
			}
			if (!Schema::hasColumn('procedimientos', 'orden_detencion')) {
				$table->boolean('orden_detencion')->default(false)->after('orden_secuestro');
			}
			// Eliminar nueva columna si existe
			if (Schema::hasColumn('procedimientos', 'orden_judicial')) {
				$table->dropColumn('orden_judicial');
			}
		});
	}
};