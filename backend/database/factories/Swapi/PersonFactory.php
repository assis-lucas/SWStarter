<?php

namespace Database\Factories\Swapi;

use App\Models\Swapi\Person;
use Illuminate\Database\Eloquent\Factories\Factory;

class PersonFactory extends Factory
{
    protected $model = Person::class;

    public function definition(): array
    {
        return [
            'swapi_id' => fake()->numberBetween(1, 100000),
            'name' => fake()->name(),
            'height' => fake()->numberBetween(150, 220),
            'mass' => fake()->numberBetween(50, 150),
            'hair_color' => fake()->randomElement(['blond', 'brown', 'black', 'red', 'n/a']),
            'skin_color' => fake()->randomElement(['fair', 'light', 'dark', 'green', 'blue']),
            'eye_color' => fake()->randomElement(['blue', 'brown', 'green', 'yellow', 'red']),
            'birth_year' => fake()->randomElement(['19BBY', '41BBY', '112BBY', 'unknown']),
            'gender' => fake()->randomElement(['male', 'female', 'n/a']),
            'homeworld_id' => null,
        ];
    }
}
