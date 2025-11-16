<?php

namespace Database\Factories\Swapi;

use App\Models\Swapi\Specie;
use Illuminate\Database\Eloquent\Factories\Factory;

class SpecieFactory extends Factory
{
    protected $model = Specie::class;

    public function definition(): array
    {
        return [
            'swapi_id' => fake()->numberBetween(1, 100000),
            'name' => fake()->word() . 'ian',
            'classification' => fake()->randomElement(['mammal', 'reptile', 'artificial', 'amphibian']),
            'designation' => fake()->randomElement(['sentient', 'non-sentient']),
            'average_height' => fake()->numberBetween(100, 250),
            'skin_colors' => fake()->randomElement(['green', 'blue', 'brown', 'tan', 'gray']),
            'hair_colors' => fake()->randomElement(['black', 'brown', 'blonde', 'n/a']),
            'eye_colors' => fake()->randomElement(['black', 'brown', 'blue', 'yellow', 'red']),
            'average_lifespan' => fake()->numberBetween(50, 1000),
            'language' => fake()->word() . 'ese',
            'homeworld_id' => null,
        ];
    }
}
