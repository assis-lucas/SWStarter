<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;
use App\Models\QueryLog;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class StatsControllerTest extends TestCase
{
    use LazilyRefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        Cache::flush();
        QueryLog::query()->delete();
    }

    private function insertQueryLog(array $attributes): void
    {
        DB::table('query_logs')->insert(array_merge([
            'bindings' => '[]',
            'full_query' => $attributes['sql'] ?? '',
            'created_at' => now(),
            'updated_at' => now(),
        ], $attributes));
    }

    public function test_stats_endpoint_returns_successful_response(): void
    {
        $response = $this->get('/stats');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'top_queries',
            'avg_query_time',
            'popular_hours',
        ]);
    }

    public function test_stats_returns_top_queries(): void
    {
        $this->insertQueryLog(['sql' => 'SELECT * FROM users', 'duration_ms' => 10]);
        $this->insertQueryLog(['sql' => 'SELECT * FROM users', 'duration_ms' => 15]);
        $this->insertQueryLog(['sql' => 'SELECT * FROM posts', 'duration_ms' => 20]);

        $response = $this->get('/stats');

        $response->assertStatus(200);
        $data = $response->json();

        $this->assertNotEmpty($data['top_queries']);
        $this->assertEquals('SELECT * FROM users', $data['top_queries'][0]['sql']);
        $this->assertEquals(2, $data['top_queries'][0]['total']);
    }

    public function test_stats_returns_average_query_time(): void
    {
        $this->insertQueryLog(['sql' => 'test1', 'duration_ms' => 10]);
        $this->insertQueryLog(['sql' => 'test2', 'duration_ms' => 20]);
        $this->insertQueryLog(['sql' => 'test3', 'duration_ms' => 30]);

        $response = $this->get('/stats');

        $response->assertStatus(200);
        $data = $response->json();

        $this->assertEquals(20, $data['avg_query_time']);
    }

    public function test_stats_returns_popular_hours(): void
    {
        $hour10 = now()->setHour(10)->setMinute(0)->setSecond(0);
        $hour14 = now()->setHour(14)->setMinute(0)->setSecond(0);
        
        $this->insertQueryLog(['sql' => 'test1', 'duration_ms' => 10, 'created_at' => $hour10]);
        $this->insertQueryLog(['sql' => 'test2', 'duration_ms' => 10, 'created_at' => $hour10]);
        $this->insertQueryLog(['sql' => 'test3', 'duration_ms' => 10, 'created_at' => $hour14]);

        $response = $this->get('/stats');

        $response->assertStatus(200);
        $data = $response->json();

        $this->assertNotEmpty($data['popular_hours']);
        $this->assertEquals(10, $data['popular_hours'][0]['hour']);
        $this->assertEquals(2, $data['popular_hours'][0]['total']);
    }

    public function test_stats_limits_top_queries_to_five(): void
    {
        for ($i = 1; $i <= 10; $i++) {
            $this->insertQueryLog(['sql' => "SELECT * FROM table_{$i}", 'duration_ms' => 10]);
        }

        $response = $this->get('/stats');

        $response->assertStatus(200);
        $data = $response->json();

        $this->assertCount(5, $data['top_queries']);
    }

    public function test_stats_are_cached(): void
    {
        Cache::flush();

        $this->insertQueryLog(['sql' => 'SELECT * FROM users', 'duration_ms' => 10]);

        $response1 = $this->get('/stats');
        $data1 = $response1->json();

        // Add more queries after first request
        $this->insertQueryLog(['sql' => 'SELECT * FROM posts', 'duration_ms' => 10]);
        $this->insertQueryLog(['sql' => 'SELECT * FROM posts', 'duration_ms' => 10]);

        $response2 = $this->get('/stats');
        $data2 = $response2->json();

        // Data should be the same because of caching
        $this->assertEquals($data1, $data2);

        // Clear cache and verify data changes
        Cache::forget('query_stats');

        $response3 = $this->get('/stats');
        $data3 = $response3->json();

        $this->assertNotEquals($data1['top_queries'], $data3['top_queries']);
    }

    public function test_stats_works_with_empty_query_log(): void
    {
        $response = $this->get('/stats');

        $response->assertStatus(200);
        $data = $response->json();

        $this->assertEmpty($data['top_queries']);
        $this->assertNull($data['avg_query_time']);
        $this->assertEmpty($data['popular_hours']);
    }
}
