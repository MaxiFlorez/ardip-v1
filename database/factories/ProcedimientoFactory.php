<?php

namespace Database\Factories;

use App\Models\Brigada;
use App\Models\Procedimiento;
use App\Models\Ufi;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<\App\Models\Procedimiento>
 */
class ProcedimientoFactory extends Factory
{
    protected $model = Procedimiento::class;

    public function definition(): array
    {
        $legajo = $this->faker->unique()->numerify('###/##');

        // ensure required relations exist for sqlite tests
        $brigadaId = Brigada::query()->firstOrCreate(['nombre' => 'Brigada Test'])->id;
        $ufiId = Ufi::query()->firstOrCreate(['nombre' => 'UFI Test'])->id;
        $usuarioId = User::factory()->create()->id;

        return [
            'legajo_fiscal' => $legajo,
            'caratula' => $this->faker->sentence(3),
            'es_positivo' => $this->faker->boolean(),
            'ufi_id' => $ufiId,
            'brigada_id' => $brigadaId,
            'usuario_id' => $usuarioId,
            'fecha_procedimiento' => $this->faker->date(),
            'hora_procedimiento' => $this->faker->time('H:i'),
            'orden_allanamiento' => false,
            'orden_secuestro' => false,
            'orden_detencion' => false,
        ];
    }
}
