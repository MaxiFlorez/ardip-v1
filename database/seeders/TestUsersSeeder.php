<?php

namespace Database\Seeders;

use App\Models\Brigada;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestUsersSeeder extends Seeder
{
    public function run(): void
    {
        // Obtener brigadas existentes
        $brigadas = Brigada::orderBy('id')->get();

        // Usuario administrador sin brigada (nullable)
        User::updateOrCreate(
            ['email' => 'admin@ardip.test'],
            [
                'name' => 'Administrador ARDIP',
                'password' => Hash::make('password'),
                'brigada_id' => null,
            ]
        );

        if ($brigadas->isEmpty()) {
            // Si no hay brigadas cargadas, salir sin crear brigadistas
            return;
        }

        // Tomar hasta 3 brigadas para asociar usuarios de prueba
        $seleccion = $brigadas->take(3)->values();

        // Usuario Brigadista 1
        User::updateOrCreate(
            ['email' => 'brigadista1@ardip.test'],
            [
                'name' => 'Brigadista 1',
                'password' => Hash::make('password'),
                'brigada_id' => $seleccion[0]->id,
            ]
        );

        // Usuario Brigadista 2 (si hay al menos 2 brigadas)
        if ($seleccion->count() > 1) {
            User::updateOrCreate(
                ['email' => 'brigadista2@ardip.test'],
                [
                    'name' => 'Brigadista 2',
                    'password' => Hash::make('password'),
                    'brigada_id' => $seleccion[1]->id,
                ]
            );
        }

        // Usuario Brigadista 3 (si hay al menos 3 brigadas)
        if ($seleccion->count() > 2) {
            User::updateOrCreate(
                ['email' => 'brigadista3@ardip.test'],
                [
                    'name' => 'Brigadista 3',
                    'password' => Hash::make('password'),
                    'brigada_id' => $seleccion[2]->id,
                ]
            );
        }
    }
}
