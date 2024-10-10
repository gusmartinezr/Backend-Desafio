<?php

namespace Database\Factories;

use App\Models\Espacio;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Espacio>
 */
class EspacioFactory extends Factory
{
    protected $model = Espacio::class;

    public function definition()
    {
        return [
            'nombre' => $this->faker->word,
            'descripcion' => $this->faker->sentence,
            'capacidad' => $this->faker->numberBetween(10, 100),
            'ubicacion' => $this->faker->address,
        ];
    }
}
