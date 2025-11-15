<?php

namespace App\Console\Commands;

use Exception;
use App\Models\Swapi\{Film, Person, Planet, Specie, Vehicle, Starship};
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Client\Swapi\SwapiLiveClient;
use App\Client\Swapi\Providers\EntityProvider;

class SyncSwapi extends Command
{
    protected $signature = 'sync-swapi';

    protected $description = 'Synchronize all SWAPI data to the database';

    public function handle(): void
    {
        $this->info("Starting SWAPI synchronization...");

        $methods = ['syncPlanets', 'syncFilms', 'syncSpecies', 'syncStarships', 'syncVehicles', 'syncPeople', 'syncFilmRelationships', 'syncPersonRelationships'];

        $bar = $this->output->createProgressBar(count($methods));
        $bar->start();

        try {
            DB::beginTransaction();

            foreach ($methods as $method) {
                $this->executeSync($method);
                $bar->advance();
            }

            DB::commit();

            $bar->finish();

            $this->info("\nSWAPI synchronization completed successfully!");
        } catch (Exception $e) {
            DB::rollBack();
            $this->error('Synchronization failed: ' . $e->getMessage());
            throw $e;
        }
    }

    private function executeSync(string $method): void
    {
        match ($method) {
            'syncPlanets' => $this->syncEntity(
                SwapiLiveClient::planet(),
                Planet::class,
                ['name', 'rotation_period', 'orbital_period', 'diameter', 'climate', 'gravity', 'terrain', 'surface_water', 'population']
            ),
            'syncFilms' => $this->syncEntity(
                SwapiLiveClient::film(),
                Film::class,
                ['title', 'episode_id', 'opening_crawl', 'director', 'producer', 'release_date']
            ),
            'syncSpecies' => $this->syncEntity(
                SwapiLiveClient::species(),
                Specie::class,
                ['name', 'classification', 'designation', 'average_height', 'skin_colors', 'hair_colors', 'eye_colors', 'average_lifespan', 'language']
            ),
            'syncStarships' => $this->syncEntity(
                SwapiLiveClient::starship(),
                Starship::class,
                ['name', 'model', 'manufacturer', 'cost_in_credits', 'length', 'max_atmosphering_speed', 'crew', 'passengers', 'cargo_capacity', 'consumables', 'hyperdrive_rating', 'MGLT', 'starship_class']
            ),
            'syncVehicles' => $this->syncEntity(
                SwapiLiveClient::vehicle(),
                Vehicle::class,
                ['name', 'model', 'manufacturer', 'cost_in_credits', 'length', 'max_atmosphering_speed', 'crew', 'passengers', 'cargo_capacity', 'consumables', 'vehicle_class']
            ),
            'syncPeople' => $this->syncPeople(),
            'syncFilmRelationships' => $this->syncFilmRelationships(),
            'syncPersonRelationships' => $this->syncPersonRelationships(),
        };
    }

    private function syncEntity(EntityProvider $provider, string $model, array $fields): array
    {
        $allData = $this->fetchAllPages($provider);
        $syncedIds = [];

        foreach ($allData as $data) {
            $swapiId = $this->extractIdFromUrl($data['url']);
            $syncedIds[] = $swapiId;

            $attributes = [];
            foreach ($fields as $field) {
                $attributes[$field] = $data[$field];
            }

            $model::updateOrCreate(
                ['swapi_id' => $swapiId],
                $attributes
            );
        }

        $model::whereNotIn('swapi_id', $syncedIds)->delete();

        return $syncedIds;
    }

    private function syncPeople(): array
    {
        $allData = $this->fetchAllPages(SwapiLiveClient::people());
        $syncedIds = [];

        foreach ($allData as $data) {
            $swapiId = $this->extractIdFromUrl($data['url']);
            $syncedIds[] = $swapiId;

            $homeworldId = null;
            if (!empty($data['homeworld'])) {
                $homeworldSwapiId = $this->extractIdFromUrl($data['homeworld']);
                $homeworld = Planet::where('swapi_id', $homeworldSwapiId)->first();
                $homeworldId = $homeworld?->id;
            }

            Person::updateOrCreate(
                ['swapi_id' => $swapiId],
                [
                    'name' => $data['name'],
                    'height' => $data['height'],
                    'mass' => $data['mass'],
                    'hair_color' => $data['hair_color'],
                    'skin_color' => $data['skin_color'],
                    'eye_color' => $data['eye_color'],
                    'birth_year' => $data['birth_year'],
                    'gender' => $data['gender'],
                    'homeworld_id' => $homeworldId,
                ]
            );
        }

        Person::whereNotIn('swapi_id', $syncedIds)->delete();

        return $syncedIds;
    }

    private function syncFilmRelationships(): void
    {
        $allFilms = $this->fetchAllPages(SwapiLiveClient::film());

        foreach ($allFilms as $filmData) {
            $filmSwapiId = $this->extractIdFromUrl($filmData['url']);
            $film = Film::where('swapi_id', $filmSwapiId)->first();

            if (!$film) {
                continue;
            }

            $characterIds = [];
            foreach ($filmData['characters'] ?? [] as $characterUrl) {
                $characterSwapiId = $this->extractIdFromUrl($characterUrl);
                $character = Person::where('swapi_id', $characterSwapiId)->first();
                if ($character) {
                    $characterIds[] = $character->id;
                }
            }
            $film->characters()->sync($characterIds);

            $planetIds = [];
            foreach ($filmData['planets'] ?? [] as $planetUrl) {
                $planetSwapiId = $this->extractIdFromUrl($planetUrl);
                $planet = Planet::where('swapi_id', $planetSwapiId)->first();
                if ($planet) {
                    $planetIds[] = $planet->id;
                }
            }
            $film->planets()->sync($planetIds);

            $speciesIds = [];
            foreach ($filmData['species'] ?? [] as $speciesUrl) {
                $speciesSwapiId = $this->extractIdFromUrl($speciesUrl);
                $species = Specie::where('swapi_id', $speciesSwapiId)->first();
                if ($species) {
                    $speciesIds[] = $species->id;
                }
            }
            $film->species()->sync($speciesIds);

            $starshipIds = [];
            foreach ($filmData['starships'] ?? [] as $starshipUrl) {
                $starshipSwapiId = $this->extractIdFromUrl($starshipUrl);
                $starship = Starship::where('swapi_id', $starshipSwapiId)->first();
                if ($starship) {
                    $starshipIds[] = $starship->id;
                }
            }
            $film->starships()->sync($starshipIds);

            $vehicleIds = [];
            foreach ($filmData['vehicles'] ?? [] as $vehicleUrl) {
                $vehicleSwapiId = $this->extractIdFromUrl($vehicleUrl);
                $vehicle = Vehicle::where('swapi_id', $vehicleSwapiId)->first();
                if ($vehicle) {
                    $vehicleIds[] = $vehicle->id;
                }
            }
            $film->vehicles()->sync($vehicleIds);
        }
    }

    private function syncPersonRelationships(): void
    {
        $allPeople = $this->fetchAllPages(SwapiLiveClient::people());

        foreach ($allPeople as $personData) {
            $personSwapiId = $this->extractIdFromUrl($personData['url']);
            $person = Person::where('swapi_id', $personSwapiId)->first();

            if (!$person) {
                continue;
            }

            $speciesIds = [];
            foreach ($personData['species'] ?? [] as $speciesUrl) {
                $speciesSwapiId = $this->extractIdFromUrl($speciesUrl);
                $species = Specie::where('swapi_id', $speciesSwapiId)->first();
                if ($species) {
                    $speciesIds[] = $species->id;
                }
            }
            $person->species()->sync($speciesIds);

            $starshipIds = [];
            foreach ($personData['starships'] ?? [] as $starshipUrl) {
                $starshipSwapiId = $this->extractIdFromUrl($starshipUrl);
                $starship = Starship::where('swapi_id', $starshipSwapiId)->first();
                if ($starship) {
                    $starshipIds[] = $starship->id;
                }
            }
            $person->starships()->sync($starshipIds);

            $vehicleIds = [];
            foreach ($personData['vehicles'] ?? [] as $vehicleUrl) {
                $vehicleSwapiId = $this->extractIdFromUrl($vehicleUrl);
                $vehicle = Vehicle::where('swapi_id', $vehicleSwapiId)->first();
                if ($vehicle) {
                    $vehicleIds[] = $vehicle->id;
                }
            }
            $person->vehicles()->sync($vehicleIds);
        }
    }

    private function fetchAllPages(EntityProvider $provider): array
    {
        $allResults = [];
        $page = 1;

        do {
            $response = $provider->get($page);
            $results = $response['results'] ?? [];
            $allResults = array_merge($allResults, $results);

            $nextPage = $response['next'] ?? null;
            $page++;
        } while ($nextPage !== null);

        return $allResults;
    }

    private function extractIdFromUrl(string $url): int
    {
        preg_match('/\/(\d+)\/$/', $url, $matches);

        return (int) $matches[1];
    }
}
