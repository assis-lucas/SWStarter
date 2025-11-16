<?php

namespace Database\Factories\Swapi;

use App\Models\Swapi\Starship;
use Illuminate\Database\Eloquent\Factories\Factory;

class StarshipFactory extends Factory
{
    protected $model = Starship::class;

    public function definition(): array
    {
        return [
            'swapi_id' => fake()->numberBetween(1, 100000),
            'name' => fake()->word() . ' ' . fake()->randomElement(['Fighter', 'Cruiser', 'Destroyer']),
            'model' => fake()->word() . '-' . fake()->numberBetween(1, 99),
            'manufacturer' => fake()->company(),
            'cost_in_credits' => fake()->numberBetween(100000, 10000000),
            'length' => fake()->numberBetween(10, 1000),
            'max_atmosphering_speed' => fake()->numberBetween(800, 1500),
            'crew' => fake()->numberBetween(1, 100),
            'passengers' => fake()->numberBetween(0, 50),
            'cargo_capacity' => fake()->numberBetween(100, 100000),
            'consumables' => fake()->randomElement(['1 week', '1 month', '1 year']),
            'hyperdrive_rating' => fake()->randomFloat(1, 0.5, 4.0),
            'MGLT' => fake()->numberBetween(50, 120),
            'starship_class' => fake()->randomElement(['Starfighter', 'Corvette', 'Frigate']),
        ];
    }
}
