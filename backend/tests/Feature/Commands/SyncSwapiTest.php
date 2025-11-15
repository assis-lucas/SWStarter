<?php

namespace Tests\Feature\Commands;

use App\Models\Swapi\{Film, Person, Planet, Specie, Vehicle, Starship};
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class SyncSwapiTest extends TestCase
{
    use LazilyRefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->mockSwapiResponses();
    }

    protected function mockSwapiResponses(): void
    {
        $planetResponse = [
            'count' => 2,
            'next' => null,
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
                    'url' => 'https://swapi.dev/api/planets/1/',
                ],
                [
                    'name' => 'Alderaan',
                    'rotation_period' => '24',
                    'orbital_period' => '364',
                    'diameter' => '12500',
                    'climate' => 'temperate',
                    'gravity' => '1 standard',
                    'terrain' => 'grasslands, mountains',
                    'surface_water' => '40',
                    'population' => '2000000000',
                    'url' => 'https://swapi.dev/api/planets/2/',
                ],
            ],
        ];

        $filmResponse = [
            'count' => 1,
            'next' => null,
            'results' => [
                [
                    'title' => 'A New Hope',
                    'episode_id' => 4,
                    'opening_crawl' => 'It is a period of civil war...',
                    'director' => 'George Lucas',
                    'producer' => 'Gary Kurtz, Rick McCallum',
                    'release_date' => '1977-05-25',
                    'url' => 'https://swapi.dev/api/films/1/',
                    'characters' => ['https://swapi.dev/api/people/1/'],
                    'planets' => ['https://swapi.dev/api/planets/1/'],
                    'species' => ['https://swapi.dev/api/species/1/'],
                    'starships' => ['https://swapi.dev/api/starships/1/'],
                    'vehicles' => ['https://swapi.dev/api/vehicles/1/'],
                ],
            ],
        ];

        $speciesResponse = [
            'count' => 1,
            'next' => null,
            'results' => [
                [
                    'name' => 'Human',
                    'classification' => 'mammal',
                    'designation' => 'sentient',
                    'average_height' => '180',
                    'skin_colors' => 'caucasian, black, asian, hispanic',
                    'hair_colors' => 'blonde, brown, black, red',
                    'eye_colors' => 'brown, blue, green, hazel, grey, amber',
                    'average_lifespan' => '120',
                    'language' => 'Galactic Basic',
                    'url' => 'https://swapi.dev/api/species/1/',
                ],
            ],
        ];

        $starshipResponse = [
            'count' => 1,
            'next' => null,
            'results' => [
                [
                    'name' => 'X-wing',
                    'model' => 'T-65 X-wing',
                    'manufacturer' => 'Incom Corporation',
                    'cost_in_credits' => '149999',
                    'length' => '12.5',
                    'max_atmosphering_speed' => '1050',
                    'crew' => '1',
                    'passengers' => '0',
                    'cargo_capacity' => '110',
                    'consumables' => '1 week',
                    'hyperdrive_rating' => '1.0',
                    'MGLT' => '100',
                    'starship_class' => 'Starfighter',
                    'url' => 'https://swapi.dev/api/starships/1/',
                ],
            ],
        ];

        $vehicleResponse = [
            'count' => 1,
            'next' => null,
            'results' => [
                [
                    'name' => 'Sand Crawler',
                    'model' => 'Digger Crawler',
                    'manufacturer' => 'Corellia Mining Corporation',
                    'cost_in_credits' => '150000',
                    'length' => '36.8',
                    'max_atmosphering_speed' => '30',
                    'crew' => '46',
                    'passengers' => '30',
                    'cargo_capacity' => '50000',
                    'consumables' => '2 months',
                    'vehicle_class' => 'wheeled',
                    'url' => 'https://swapi.dev/api/vehicles/1/',
                ],
            ],
        ];

        $peopleResponse = [
            'count' => 1,
            'next' => null,
            'results' => [
                [
                    'name' => 'Luke Skywalker',
                    'height' => '172',
                    'mass' => '77',
                    'hair_color' => 'blond',
                    'skin_color' => 'fair',
                    'eye_color' => 'blue',
                    'birth_year' => '19BBY',
                    'gender' => 'male',
                    'homeworld' => 'https://swapi.dev/api/planets/1/',
                    'url' => 'https://swapi.dev/api/people/1/',
                    'films' => ['https://swapi.dev/api/films/1/'],
                    'species' => ['https://swapi.dev/api/species/1/'],
                    'starships' => ['https://swapi.dev/api/starships/1/'],
                    'vehicles' => ['https://swapi.dev/api/vehicles/1/'],
                ],
            ],
        ];

        Http::fake([
            'https://swapi.dev/api/planets/*' => Http::response($planetResponse),
            'https://swapi.dev/api/films/*' => Http::response($filmResponse),
            'https://swapi.dev/api/species/*' => Http::response($speciesResponse),
            'https://swapi.dev/api/starships/*' => Http::response($starshipResponse),
            'https://swapi.dev/api/vehicles/*' => Http::response($vehicleResponse),
            'https://swapi.dev/api/people/*' => Http::response($peopleResponse),
        ]);
    }

    public function test_sync_swapi_command_syncs_all_entities(): void
    {
        $this->artisan('sync-swapi')->assertSuccessful();

        $this->assertEquals(1, Film::count());
        $this->assertEquals(1, Person::count());
        $this->assertEquals(2, Planet::count());
        $this->assertEquals(1, Specie::count());
        $this->assertEquals(1, Starship::count());
        $this->assertEquals(1, Vehicle::count());
    }

    public function test_sync_swapi_command_does_not_duplicate_data(): void
    {
        $this->artisan('sync-swapi')->assertSuccessful();

        $filmCount = Film::count();
        $peopleCount = Person::count();
        $planetCount = Planet::count();

        $this->artisan('sync-swapi')->assertSuccessful();

        $this->assertEquals($filmCount, Film::count());
        $this->assertEquals($peopleCount, Person::count());
        $this->assertEquals($planetCount, Planet::count());
    }

    public function test_sync_swapi_command_deletes_orphaned_records(): void
    {
        $this->artisan('sync-swapi')->assertSuccessful();

        Planet::create([
            'swapi_id' => 99999,
            'name' => 'Test Planet to Delete',
            'rotation_period' => '24',
            'orbital_period' => '365',
            'diameter' => '10000',
            'climate' => 'temperate',
            'gravity' => '1',
            'terrain' => 'grasslands',
            'surface_water' => '40',
            'population' => '1000000',
            'url' => 'https://swapi.dev/api/planets/99999/',
        ]);

        $this->assertTrue(Planet::where('swapi_id', 99999)->exists());

        $this->artisan('sync-swapi')->assertSuccessful();

        $this->assertFalse(Planet::where('swapi_id', 99999)->exists());
    }

    public function test_sync_swapi_command_creates_relationships(): void
    {
        $this->artisan('sync-swapi')->assertSuccessful();

        $film = Film::where('title', 'A New Hope')->first();
        $this->assertNotNull($film);
        $this->assertGreaterThan(0, $film->characters()->count());
        $this->assertGreaterThan(0, $film->planets()->count());

        $person = Person::where('name', 'Luke Skywalker')->first();
        $this->assertNotNull($person);
        $this->assertNotNull($person->homeworld);
        $this->assertGreaterThan(0, $person->films()->count());
    }
}
