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
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        // Users without a role default to personas index
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
        $user = User::factory()->create();
        $adminRole = \App\Models\Role::create(['name' => 'admin', 'label' => 'Administrator']);
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
        $user = User::factory()->create();
        $cargadorRole = \App\Models\Role::create(['name' => 'cargador', 'label' => 'Cargador']);
        $user->roles()->attach($cargadorRole);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('panel.carga', absolute: false));
    }

    public function test_consultor_users_redirect_to_personas_index(): void
    {
        $user = User::factory()->create();
        $consultorRole = \App\Models\Role::create(['name' => 'consultor', 'label' => 'Consultor']);
        $user->roles()->attach($consultorRole);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('panel.consulta', absolute: false));
    }
}
