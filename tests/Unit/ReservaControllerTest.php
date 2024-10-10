<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Espacio;
use App\Models\Reserva;

class ReservaControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_cannot_create_reserva_with_conflict()
    {
        $user = User::factory()->create();
        $espacio = Espacio::factory()->create();

        // Crear una reserva existente
        Reserva::factory()->create([
            'espacio_id' => $espacio->id,
            'user_id' => $user->id, // Agregar el user_id
            'fecha_inicio' => now()->addDay(),
            'fecha_fin' => now()->addDays(2),
        ]);

        // Intentar crear una reserva que se superpone
        $response = $this->actingAs($user, 'api')->postJson('/api/reservas', [
            'espacio_id' => $espacio->id,
            'user_id' => $user->id, // Agregar el user_id
            'nombre_evento' => 'Evento en conflicto',
            'fecha_inicio' => now()->addDays(1),
            'fecha_fin' => now()->addDays(3),
        ]);

        $response->assertStatus(409);
    }
}
