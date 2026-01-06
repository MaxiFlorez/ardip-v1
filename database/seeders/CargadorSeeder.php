<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CargadorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role = Role::whereIn('name', ['cargador', 'panel-carga'])->firstOrFail();

        $user = User::firstOrCreate(
            ['email' => 'cargador@gmail.com'],
            [
                'name' => 'Oficial Cargador',
                'password' => Hash::make('123456789'),
                'active' => true,
                'remember_token' => Str::random(10),
            ]
        );

        $user->roles()->syncWithoutDetaching([$role->id]);
    }
}
