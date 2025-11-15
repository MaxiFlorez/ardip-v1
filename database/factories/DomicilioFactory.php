<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Domicilio;

class DomicilioFactory extends Factory
{
    protected $model = Domicilio::class;

    public function definition(): array
    {
        return [
            'calle' => $this->faker->streetName(),
            'numero' => $this->faker->buildingNumber(),
            'sector' => $this->faker->optional()->word(),
            'piso' => $this->faker->optional()->randomDigit(),
            'depto' => $this->faker->optional()->bothify('A?'),
            'torre' => $this->faker->optional()->bothify('T##'),
            'monoblock' => $this->faker->optional()->bothify('M##'),
            'manzana' => $this->faker->optional()->bothify('Mz##'),
            'lote' => $this->faker->optional()->bothify('L##'),
            'casa' => $this->faker->optional()->bothify('C##'),
            'coordenadas_gps' => $this->faker->optional()->latitude() . ',' . $this->faker->longitude(),
            'provincia_id' => null,
            'departamento_id' => null,
            'barrio' => $this->faker->optional()->streetName(),
        ];
    }
}
