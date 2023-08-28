<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Alquiler>
 */
class AlquilerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'hora_comienzo' => $this->faker->dateTimeBetween('-10 hour', '-5 hour'),
            'hora_final' => null,
            'precio_total' => null,
            'user_id' => $this->faker->numberBetween(2, 10),
            'bicicleta_id' => $this->faker->unique()->numberBetween(1, 30),
        ];
    }

    public function alquilerSinTerminar(): AlquilerFactory
    {
        return $this->state(function (array $attributes) {
            return [
                'hora_final' => null,
            ];
        });
    }

    public function alquilerTerminado(): AlquilerFactory
    {
        return $this->state(function (array $attributes) {
            return [
                'hora_final' => $this->faker->dateTimeBetween('-4 hour', 'now'),
                'precio_total' => $this->faker->randomFloat(0, 100, 1000),
            ];
        });
    }

    public function perteneceAUsuario(int $user_id): AlquilerFactory
    {
        return $this->state(function (array $attributes) use ($user_id) {
            return [
                'user_id' => $user_id,
            ];
        });
    }
}
