<?php

namespace Database\Factories\Swapi;

use App\Models\Swapi\Film;
use Illuminate\Database\Eloquent\Factories\Factory;

class FilmFactory extends Factory
{
    protected $model = Film::class;

    public function definition(): array
    {
        return [
            'swapi_id' => fake()->numberBetween(1, 100000),
            'title' => fake()->sentence(3),
            'episode_id' => fake()->numberBetween(1, 9),
            'opening_crawl' => fake()->paragraphs(3, true),
            'director' => fake()->name(),
            'producer' => fake()->name(),
            'release_date' => fake()->date(),
        ];
    }
}
