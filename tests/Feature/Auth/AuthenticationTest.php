<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_users_can_authenticate_using_the_login_screen(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        // Rol por defecto: panel-consulta (redirección a personas.index)
        $consultorRole = \App\Models\Role::create(['name' => 'panel-consulta', 'label' => 'Visor de Consultas']);
        $user->roles()->attach($consultorRole);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('personas.index', absolute: false));
    }

    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        $user = User::factory()->create();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }

    public function test_users_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect(route('login'));
    }

    public function test_admin_users_redirect_to_dashboard(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $adminRole = \App\Models\Role::create(['name' => 'admin', 'label' => 'Administrador del Sistema']);
        $user->roles()->attach($adminRole);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));
    }

    public function test_cargador_users_redirect_to_procedimientos_create(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $cargadorRole = \App\Models\Role::create(['name' => 'panel-carga', 'label' => 'Operador de Carga']);
        $user->roles()->attach($cargadorRole);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('procedimientos.index', absolute: false));
    }

    public function test_consultor_users_redirect_to_personas_index(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $consultorRole = \App\Models\Role::create(['name' => 'panel-consulta', 'label' => 'Visor de Consultas']);
        $user->roles()->attach($consultorRole);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('personas.index', absolute: false));
    }
}
