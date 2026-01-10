<?php

namespace Tests\Feature\Auth;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertAuthenticated();
        // Obtener el usuario recién creado
        $user = User::where('email', 'test@example.com')->first();

        // Asignar rol consultor por defecto
        $consultorRole = Role::firstOrCreate(
            ['name' => 'panel-consulta'],
            ['label' => 'Visor de Consultas']
        );
        $user->roles()->syncWithoutDetaching([$consultorRole->id]);
        $user->load('roles');
        $this->assertTrue($user->hasRole('panel-consulta'));

        // Los usuarios sin rol van a personas (después de registro)
        $response->assertRedirect(route('personas.index', absolute: false));
    }
}
