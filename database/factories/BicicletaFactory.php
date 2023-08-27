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
        // Create bicicletas  but with foto_url from imgs/bicicletas/bicicleta1.jpg to bicicleta5.jpg

        return [
            'marca' => $this->faker->company,
            'modelo' => $this->faker->lastName,
            'precio_por_hora' => $this->faker->randomFloat(0, 100, 1000),
            'foto_url' => 'imgs/bicicletas/bicicleta' . $this->faker->numberBetween(1, 8) . '.png',
        ];
    }
}
