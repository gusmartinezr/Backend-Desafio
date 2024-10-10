<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Espacio;

class EspacioControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_espacios()
    {
        $user = User::factory()->create();
        Espacio::factory()->count(3)->create();

        $response = $this->actingAs($user, 'api')->getJson('/api/espacios');

        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    public function test_can_create_espacio()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'api')->postJson('/api/espacios', [
            'nombre' => 'Sala A',
            'capacidad' => 100,
        ]);

        $response->assertStatus(201)
            ->assertJson(['nombre' => 'Sala A']);
    }

    public function test_can_update_espacio()
    {
        $user = User::factory()->create();
        $espacio = Espacio::factory()->create(['nombre' => 'Sala B']);

        $response = $this->actingAs($user, 'api')->putJson("/api/espacios/{$espacio->id}", [
            'nombre' => 'Sala B actualizada',
            'capacidad' => 150,
        ]);

        $response->assertStatus(200)
            ->assertJson(['nombre' => 'Sala B actualizada']);
    }

    public function test_can_delete_espacio()
    {
        $user = User::factory()->create();
        $espacio = Espacio::factory()->create();

        $response = $this->actingAs($user, 'api')->deleteJson("/api/espacios/{$espacio->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('espacios', ['id' => $espacio->id]);
    }
}
