<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Super Administrador',
                'email' => 'superadmin@ardip.gob.ar',
                'password' => Hash::make('123456789'),
                'role' => 'super_admin',
            ],
            [
                'name' => 'Administrador del Sistema',
                'email' => 'admin@ardip.gob.ar',
                'password' => Hash::make('123456789'),
                'role' => 'admin',
            ],
            [
                'name' => 'Operador de Carga',
                'email' => 'cargador@ardip.gob.ar',
                'password' => Hash::make('123456789'),
                'role' => 'panel-carga',
            ],
            [
                'name' => 'Consultor',
                'email' => 'consultor@ardip.gob.ar',
                'password' => Hash::make('123456789'),
                'role' => 'panel-consulta',
            ],
        ];

        foreach ($users as $userData) {
            $role = $userData['role'];
            unset($userData['role']);

            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                array_merge($userData, ['active' => true])
            );

            $roleModel = Role::where('name', $role)->first();
            if ($roleModel) {
                $user->roles()->syncWithoutDetaching([$roleModel->id]);
            }
        }
    }
}
