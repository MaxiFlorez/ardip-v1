<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     * Orquesta la ejecución de todos los seeders en orden.
     */
    public function run(): void
    {
        $this->call([
            // 1. Crear roles (debe ser primero)
            RoleSeeder::class,
            
            // 2. Crear datos operativos
            BrigadaSeeder::class,
            UfiSeeder::class,
            
            // 3. Crear usuarios con roles asignados (después de roles)
            UserSeeder::class,
        ]);
    }
}
