<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Puedes mantener factories aquí si las necesitas
        // User::factory(10)->create();

        // Llama a los seeders en el orden correcto de dependencia
        $this->call([
            // 1. Catálogos que no dependen de nada
            BrigadaSeeder::class,
            ProvinciaSeeder::class,
            DepartamentoSeeder::class,
            // 2. Usuarios de prueba que dependen de Brigadas
            TestUsersSeeder::class,
        ]);
    }
}
