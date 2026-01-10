<?php

namespace Tests\Feature\Auth;

use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_email_verification_screen_can_be_rendered(): void
    {
        $user = User::factory()->unverified()->create();

        $response = $this->actingAs($user)->get('/verify-email');

        $response->assertStatus(200);
    }

    public function test_email_can_be_verified(): void
    {
        // Crear usuario sin email verificado, rol admin para evitar gates
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        $adminRole = Role::firstOrCreate(
            ['name' => 'admin'],
            ['label' => 'Administrador del Sistema']
        );
        $user->roles()->attach($adminRole);
        $user->load('roles');

        Event::fake();

        // Generar URL firmada de verificación
        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        // Visitar la URL de verificación
        $response = $this->actingAs($user)->get($verificationUrl);

        // Verificar que se disparó el evento Verified
        Event::assertDispatched(Verified::class);
        
        // Verificar que el email fue marcado como verificado
        $this->assertTrue($user->fresh()->hasVerifiedEmail());
        
        // Redirige al home configurado (dashboard) con flag verified
        $response->assertRedirect(route('dashboard', absolute: false).'?verified=1');
    }

    public function test_email_is_not_verified_with_invalid_hash(): void
    {
        $user = User::factory()->unverified()->create();

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1('wrong-email')]
        );

        $this->actingAs($user)->get($verificationUrl);

        $this->assertFalse($user->fresh()->hasVerifiedEmail());
    }
}
