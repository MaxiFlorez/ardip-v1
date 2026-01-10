<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     * Solo crea los roles. La asignación de roles a usuarios se hace en UserSeeder.
     */
    public function run(): void
    {
        $roles = [
            ['name' => 'super_admin', 'label' => 'Super Administrador'],
            ['name' => 'admin', 'label' => 'Administrador del Sistema'],
            ['name' => 'panel-carga', 'label' => 'Operador de Carga'],
            ['name' => 'panel-consulta', 'label' => 'Visor de Consultas'],
        ];

        foreach ($roles as $r) {
            Role::firstOrCreate(['name' => $r['name']], ['label' => $r['label']]);
        }
    }
}
