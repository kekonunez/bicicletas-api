<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Bicicleta>
 */
class BicicletaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'marca' => $this->faker->word,
            'modelo' => $this->faker->word,
            'precio_por_hora' => $this->faker->randomFloat(0, 100, 1000),
            'foto_url' => 'imgs/bicicletas/bici'
        ];
    }
}
