<?php

namespace Tests\Feature;

use App\Models\Procedimiento;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProcedimientoFilterTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    /**
     * setUp se ejecuta antes de CADA test method.
     * Crea el rol, el usuario y lo autentica globalmente.
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Crear el rol panel-carga si no existe
        $role = Role::firstOrCreate(
            ['name' => 'panel-carga'],
            ['label' => 'Operador de Carga']
        );

        // Crear usuario de prueba global
        $this->user = User::factory()->create([
            'name' => 'Cargador Test',
            'email' => 'cargador.test@ardip.gob.ar',
            'email_verified_at' => now(),
        ]);

        // Asignar rol panel-carga al usuario
        $this->user->roles()->attach($role->id);

        // Autenticar globalmente para todos los tests
        $this->actingAs($this->user);
    }

    /**
     * Test: Filtrar procedimientos por legajo_fiscal
     */
    public function test_can_filter_procedimientos()
    {
        // Crear datos de prueba usando factory
        Procedimiento::factory()->create(['legajo_fiscal' => '123/23']);
        Procedimiento::factory()->create(['legajo_fiscal' => '456/23']);

        // Hacer request a ruta protegida con filtro
        $response = $this->get(route('procedimientos.index', ['legajo_fiscal' => '123/23']));

        // Assertions
        $response->assertStatus(200);
        $response->assertSee('123/23');
        $response->assertDontSee('456/23');
    }

    /**
     * Test: Filtrar procedimientos por caratula
     */
    public function test_can_filter_procedimientos_by_caratula()
    {
        // Crear datos de prueba usando factory
        Procedimiento::factory()->create(['caratula' => 'Test Caratula 1']);
        Procedimiento::factory()->create(['caratula' => 'Another Caratula']);

        // Hacer request a ruta protegida con filtro
        $response = $this->get(route('procedimientos.index', ['caratula' => 'Test Caratula 1']));

        // Assertions
        $response->assertStatus(200);
        $response->assertSee('Test Caratula 1');
        $response->assertDontSee('Another Caratula');
    }
}
