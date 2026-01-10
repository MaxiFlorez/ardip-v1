<?php

namespace Tests\Feature\Auth;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * DESACTIVADO: Sistema cerrado - el registro público está deshabilitado.
     * Solo los administradores pueden crear usuarios mediante Admin/UserController.
     */
    public function test_registration_is_disabled_404(): void
    {
        $response = $this->get('/register');

        // Ruta de registro retorna 404 porque está comentada en routes/auth.php
        $response->assertStatus(404);
    }

    /**
     * DESACTIVADO: Sistema cerrado.
     * Las pruebas de registro POST también fallarían ahora.
     */
    public function test_new_users_cannot_register_system_closed(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        // POST a /register también retorna 404
        $response->assertStatus(404);
        
        // Confirmar que el usuario NO fue creado
        $this->assertNull(User::where('email', 'test@example.com')->first());
    }
}
