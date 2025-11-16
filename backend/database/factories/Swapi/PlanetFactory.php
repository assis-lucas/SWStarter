<?php

namespace Database\Factories\Swapi;

use App\Models\Swapi\Planet;
use Illuminate\Database\Eloquent\Factories\Factory;

class PlanetFactory extends Factory
{
    protected $model = Planet::class;

    public function definition(): array
    {
        return [
            'swapi_id' => fake()->numberBetween(1, 100000),
            'name' => fake()->word() . ' ' . fake()->randomElement(['Prime', 'IV', 'Major', 'Minor']),
            'rotation_period' => fake()->numberBetween(10, 50),
            'orbital_period' => fake()->numberBetween(200, 500),
            'diameter' => fake()->numberBetween(5000, 20000),
            'climate' => fake()->randomElement(['arid', 'temperate', 'tropical', 'frozen']),
            'gravity' => fake()->randomElement(['1 standard', '0.5 standard', '1.5 standard']),
            'terrain' => fake()->randomElement(['desert', 'grasslands', 'mountains', 'jungle']),
            'surface_water' => fake()->numberBetween(0, 100),
            'population' => fake()->numberBetween(1000, 10000000),
        ];
    }
}
