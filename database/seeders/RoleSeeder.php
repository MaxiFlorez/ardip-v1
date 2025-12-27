<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['name' => 'admin', 'label' => 'Administrador del Sistema'],
            ['name' => 'cargador', 'label' => 'Operador de Carga'],
            ['name' => 'consultor', 'label' => 'Visor de Consultas'],
        ];

        foreach ($roles as $r) {
            Role::firstOrCreate(['name' => $r['name']], ['label' => $r['label']]);
        }

        $firstUser = User::first();
        $adminRole = Role::where('name', 'admin')->first();
        $cargadorRole = Role::where('name', 'cargador')->first();

        if ($firstUser && $adminRole) {
            $firstUser->roles()->syncWithoutDetaching([$adminRole->id]);
        }

        if ($cargadorRole) {
            User::where('id', '!=', $firstUser?->id ?? 0)->get()->each(function (User $user) use ($cargadorRole) {
                $user->roles()->syncWithoutDetaching([$cargadorRole->id]);
            });
        }
    }
}
