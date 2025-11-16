<?php

namespace Tests\Feature;

use App\Models\Swapi\Film;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class FilmsControllerTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_index_returns_paginated_films(): void
    {
        Film::factory()->count(15)->create();

        $response = $this->getJson(route('films.index'));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'swapi_id',
                        'title',
                        'episode_id',
                        'opening_crawl',
                        'director',
                        'producer',
                        'release_date',
                    ]
                ],
                'links',
                'meta' => [
                    'current_page',
                    'last_page',
                    'per_page',
                    'total',
                ]
            ]);

        $this->assertEquals(10, count($response->json('data')));
        $this->assertEquals(15, $response->json('meta.total'));
    }

    public function test_index_includes_relations_when_loaded(): void
    {
        Film::factory()->create();

        $response = $this->getJson(route('films.index'));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'characters',
                        'planets',
                        'starships',
                        'vehicles',
                        'species',
                    ]
                ]
            ]);
    }

    public function test_index_filters_by_search_in_title(): void
    {
        Film::factory()->create(['title' => 'A New Hope']);
        Film::factory()->create(['title' => 'The Empire Strikes Back']);
        Film::factory()->create(['title' => 'Return of the Jedi']);

        $response = $this->getJson(route('films.index', ['search' => 'Hope']));

        $response->assertStatus(200);
        $this->assertEquals(1, count($response->json('data')));
        $this->assertStringContainsString('Hope', $response->json('data.0.title'));
    }

    public function test_index_filters_by_search_in_opening_crawl(): void
    {
        Film::factory()->create([
            'title' => 'Test Film 1',
            'opening_crawl' => 'A long time ago in a GALAXY far far away',
        ]);
        Film::factory()->create([
            'title' => 'Test Film 2',
            'opening_crawl' => 'The Empire is rising',
        ]);

        $response = $this->getJson(route('films.index', ['search' => 'galaxy']));

        $response->assertStatus(200);
        $this->assertEquals(1, count($response->json('data')));
        $this->assertStringContainsString('GALAXY', $response->json('data.0.opening_crawl'));
    }

    public function test_index_returns_empty_when_no_films_exist(): void
    {
        $response = $this->getJson(route('films.index'));

        $response->assertStatus(200)
            ->assertJson([
                'data' => [],
                'meta' => [
                    'total' => 0
                ]
            ]);
    }

    public function test_index_pagination_works_correctly(): void
    {
        Film::factory()->count(25)->create();

        $response1 = $this->getJson(route('films.index', ['page' => 1]));
        $response2 = $this->getJson(route('films.index', ['page' => 2]));
        $response3 = $this->getJson(route('films.index', ['page' => 3]));

        $response1->assertStatus(200);
        $response2->assertStatus(200);
        $response3->assertStatus(200);

        $this->assertEquals(10, count($response1->json('data')));
        $this->assertEquals(10, count($response2->json('data')));
        $this->assertEquals(5, count($response3->json('data')));

        $this->assertEquals(1, $response1->json('meta.current_page'));
        $this->assertEquals(2, $response2->json('meta.current_page'));
        $this->assertEquals(3, $response3->json('meta.current_page'));
    }

    public function test_show_returns_single_film_with_relations(): void
    {
        Film::factory()->create(['title' => 'Test Film']);

        $response = $this->getJson(route('films.show', Film::first()->id));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'swapi_id',
                    'title',
                    'episode_id',
                    'opening_crawl',
                    'director',
                    'producer',
                    'release_date',
                    'characters',
                    'planets',
                    'starships',
                    'vehicles',
                    'species',
                ]
            ])
            ->assertJson([
                'data' => [
                    'title' => 'Test Film',
                ]
            ]);
    }

    public function test_show_returns_404_for_nonexistent_film(): void
    {
        $response = $this->getJson(route('films.show', 999999));

        $response->assertStatus(404);
    }
}
