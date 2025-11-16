<?php

namespace Database\Factories\Swapi;

use App\Models\Swapi\Vehicle;
use Illuminate\Database\Eloquent\Factories\Factory;

class VehicleFactory extends Factory
{
    protected $model = Vehicle::class;

    public function definition(): array
    {
        return [
            'swapi_id' => fake()->numberBetween(1, 100000),
            'name' => fake()->word() . ' ' . fake()->randomElement(['Speeder', 'Walker', 'Transport']),
            'model' => fake()->word() . '-' . fake()->numberBetween(1, 99),
            'manufacturer' => fake()->company(),
            'cost_in_credits' => fake()->numberBetween(10000, 500000),
            'length' => fake()->numberBetween(5, 50),
            'max_atmosphering_speed' => fake()->numberBetween(100, 500),
            'crew' => fake()->numberBetween(1, 10),
            'passengers' => fake()->numberBetween(0, 20),
            'cargo_capacity' => fake()->numberBetween(50, 10000),
            'consumables' => fake()->randomElement(['1 day', '1 week', '1 month']),
            'vehicle_class' => fake()->randomElement(['speeder', 'walker', 'transport']),
        ];
    }
}
