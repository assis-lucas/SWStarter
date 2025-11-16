<?php

namespace Database\Factories;

use App\Models\QueryLog;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\QueryLog>
 */
class QueryLogFactory extends Factory
{
    protected $model = QueryLog::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $sql = $this->faker->randomElement([
            'SELECT * FROM users',
            'SELECT * FROM posts',
            'INSERT INTO users VALUES (?, ?)',
            'UPDATE posts SET title = ? WHERE id = ?',
            'DELETE FROM comments WHERE id = ?',
        ]);

        $bindings = json_encode($this->faker->randomElements([
            $this->faker->numberBetween(1, 100),
            $this->faker->word(),
            $this->faker->email(),
        ], $this->faker->numberBetween(0, 3)));

        return [
            'sql' => $sql,
            'bindings' => $bindings,
            'duration_ms' => $this->faker->randomFloat(2, 0.1, 1000),
            'full_query' => $sql,
            'created_at' => $this->faker->dateTimeBetween('-1 week', 'now'),
            'updated_at' => now(),
        ];
    }
}
