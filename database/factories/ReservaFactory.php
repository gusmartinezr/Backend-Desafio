<?php

namespace Database\Factories;

use App\Models\Reserva;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reserva>
 */
class ReservaFactory extends Factory
{
    protected $model = Reserva::class;

    public function definition()
    {
        return [
            'nombre_evento' => $this->faker->sentence,
            'fecha_inicio' => $this->faker->dateTimeBetween('now', '+1 week'),
            'fecha_fin' => $this->faker->dateTimeBetween('+1 week', '+2 weeks'),
            'espacio_id' => \App\Models\Espacio::factory(),
        ];
    }
}
