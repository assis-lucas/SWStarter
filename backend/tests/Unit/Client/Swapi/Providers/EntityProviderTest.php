<?php

namespace Tests\Unit\Client\Swapi\Providers;

use App\Client\Swapi\Providers\EntityProvider;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class EntityProviderTest extends TestCase
{
    public function test_get_fetches_paginated_data(): void
    {
        Http::fake([
            'https://swapi.dev/api/planets/*' => Http::response([
                'count' => 60,
                'next' => 'https://swapi.dev/api/planets/?page=2',
                'previous' => null,
                'results' => [
                    [
                        'name' => 'Tatooine',
                        'rotation_period' => '23',
                        'orbital_period' => '304',
                        'diameter' => '10465',
                        'climate' => 'arid',
                        'gravity' => '1 standard',
                        'terrain' => 'desert',
                        'surface_water' => '1',
                        'population' => '200000',
                    ]
                ]
            ], 200)
        ]);

        $provider = new EntityProvider('https://swapi.dev/api', 'planets');
        $result = $provider->get(1);

        $this->assertArrayHasKey('count', $result);
        $this->assertArrayHasKey('results', $result);
        $this->assertEquals(60, $result['count']);
        $this->assertCount(1, $result['results']);
        $this->assertEquals('Tatooine', $result['results'][0]['name']);

        Http::assertSent(function ($request) {
            return $request->url() === 'https://swapi.dev/api/planets/?page=1' &&
                   $request->hasHeader('Accept', 'application/json');
        });
    }

    public function test_get_with_different_page_number(): void
    {
        Http::fake([
            'https://swapi.dev/api/films/*' => Http::response([
                'count' => 6,
                'next' => null,
                'previous' => 'https://swapi.dev/api/films/?page=1',
                'results' => [
                    [
                        'title' => 'The Empire Strikes Back',
                        'episode_id' => 5,
                        'director' => 'Irvin Kershner',
                    ]
                ]
            ], 200)
        ]);

        $provider = new EntityProvider('https://swapi.dev/api', 'films');
        $result = $provider->get(2);

        $this->assertArrayHasKey('results', $result);
        $this->assertEquals('The Empire Strikes Back', $result['results'][0]['title']);

        Http::assertSent(function ($request) {
            return $request->url() === 'https://swapi.dev/api/films/?page=2';
        });
    }

    public function test_find_fetches_single_entity(): void
    {
        Http::fake([
            'https://swapi.dev/api/people/1' => Http::response([
                'name' => 'Luke Skywalker',
                'height' => '172',
                'mass' => '77',
                'hair_color' => 'blond',
                'skin_color' => 'fair',
                'eye_color' => 'blue',
                'birth_year' => '19BBY',
                'gender' => 'male',
            ], 200)
        ]);

        $provider = new EntityProvider('https://swapi.dev/api', 'people');
        $result = $provider->find(1);

        $this->assertArrayHasKey('name', $result);
        $this->assertEquals('Luke Skywalker', $result['name']);
        $this->assertEquals('172', $result['height']);

        Http::assertSent(function ($request) {
            return $request->url() === 'https://swapi.dev/api/people/1' &&
                   $request->hasHeader('Accept', 'application/json');
        });
    }

    public function test_find_with_different_endpoints(): void
    {
        Http::fake([
            'https://swapi.dev/api/starships/9' => Http::response([
                'name' => 'Death Star',
                'model' => 'DS-1 Orbital Battle Station',
                'manufacturer' => 'Imperial Department of Military Research',
                'cost_in_credits' => '1000000000000',
                'length' => '120000',
                'crew' => '342953',
            ], 200)
        ]);

        $provider = new EntityProvider('https://swapi.dev/api', 'starships');
        $result = $provider->find(9);

        $this->assertEquals('Death Star', $result['name']);
        $this->assertEquals('DS-1 Orbital Battle Station', $result['model']);

        Http::assertSent(function ($request) {
            return $request->url() === 'https://swapi.dev/api/starships/9';
        });
    }

    public function test_get_for_species(): void
    {
        Http::fake([
            'https://swapi.dev/api/species/*' => Http::response([
                'count' => 37,
                'next' => null,
                'previous' => null,
                'results' => [
                    [
                        'name' => 'Wookie',
                        'classification' => 'mammal',
                        'designation' => 'sentient',
                        'average_height' => '210',
                        'skin_colors' => 'gray',
                        'hair_colors' => 'black, brown',
                        'eye_colors' => 'blue, green, yellow, brown, golden, red',
                        'average_lifespan' => '400',
                    ]
                ]
            ], 200)
        ]);

        $provider = new EntityProvider('https://swapi.dev/api', 'species');
        $result = $provider->get();

        $this->assertArrayHasKey('results', $result);
        $this->assertEquals('Wookie', $result['results'][0]['name']);
    }

    public function test_get_for_vehicles(): void
    {
        Http::fake([
            'https://swapi.dev/api/vehicles/*' => Http::response([
                'count' => 39,
                'next' => null,
                'previous' => null,
                'results' => [
                    [
                        'name' => 'Sand Crawler',
                        'model' => 'Digger Crawler',
                        'manufacturer' => 'Corellia Mining Corporation',
                        'cost_in_credits' => '150000',
                        'length' => '36.8',
                        'crew' => '46',
                    ]
                ]
            ], 200)
        ]);

        $provider = new EntityProvider('https://swapi.dev/api', 'vehicles');
        $result = $provider->get();

        $this->assertArrayHasKey('results', $result);
        $this->assertEquals('Sand Crawler', $result['results'][0]['name']);
    }
}
