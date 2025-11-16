<?php

namespace Tests\Feature;

use App\Models\Swapi\Person;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class PeopleControllerTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_index_returns_paginated_people(): void
    {
        Person::factory()->count(15)->create();

        $response = $this->getJson(route('people.index'));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'swapi_id',
                        'name',
                        'height',
                        'mass',
                        'hair_color',
                        'skin_color',
                        'eye_color',
                        'birth_year',
                        'gender',
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
        Person::factory()->create();

        $response = $this->getJson(route('people.index', ['page' => 1]));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'homeworld',
                        'films',
                        'species',
                        'starships',
                        'vehicles',
                    ]
                ]
            ]);
    }

    public function test_index_returns_empty_when_no_people_exist(): void
    {
        $response = $this->getJson(route('people.index'));

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
        Person::factory()->count(25)->create();

        $response1 = $this->getJson(route('people.index', ['page' => 1]));
        $response2 = $this->getJson(route('people.index', ['page' => 2]));
        $response3 = $this->getJson(route('people.index', ['page' => 3]));

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

    public function test_show_returns_single_person_with_relations(): void
    {
        Person::factory()->create(['name' => 'Test Person']);

        $response = $this->getJson(route('people.show', Person::first()->id));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'swapi_id',
                    'name',
                    'height',
                    'mass',
                    'hair_color',
                    'skin_color',
                    'eye_color',
                    'birth_year',
                    'gender',
                    'homeworld',
                    'films',
                    'species',
                    'starships',
                    'vehicles',
                ]
            ])
            ->assertJson([
                'data' => [
                    'name' => 'Test Person',
                ]
            ]);
    }

    public function test_show_returns_404_for_nonexistent_person(): void
    {
        $response = $this->getJson(route('people.show', 999999));

        $response->assertStatus(404);
    }
}
