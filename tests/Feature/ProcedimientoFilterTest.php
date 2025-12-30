<?php

namespace Tests\Feature;

use App\Models\Procedimiento;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProcedimientoFilterTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_filter_procedimientos()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Procedimiento::factory()->create(['legajo_fiscal' => '123/23']);
        Procedimiento::factory()->create(['legajo_fiscal' => '456/23']);

        $response = $this->get(route('procedimientos.index', ['legajo_fiscal' => '123/23']));

        $response->assertStatus(200);
        $response->assertSee('123/23');
        $response->assertDontSee('456/23');
    }

    public function test_can_filter_procedimientos_by_caratula()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Procedimiento::factory()->create(['caratula' => 'Test Caratula 1']);
        Procedimiento::factory()->create(['caratula' => 'Another Caratula']);

        $response = $this->get(route('procedimientos.index', ['caratula' => 'Test Caratula 1']));

        $response->assertStatus(200);
        $response->assertSee('Test Caratula 1');
        $response->assertDontSee('Another Caratula');
    }
}
