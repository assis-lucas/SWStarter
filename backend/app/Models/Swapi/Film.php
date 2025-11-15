<?php

namespace App\Models\Swapi;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Film extends Model
{
    protected $table = 'swapi_films';

    protected $fillable = [
        'swapi_id',
        'title',
        'episode_id',
        'opening_crawl',
        'director',
        'producer',
        'release_date'
    ];

    protected $casts = [
        'release_date' => 'date',
    ];

    public function characters(): BelongsToMany
    {
        return $this->belongsToMany(Person::class, 'swapi_film_person', 'film_id', 'person_id');
    }

    public function planets(): BelongsToMany
    {
        return $this->belongsToMany(Planet::class, 'swapi_film_planet', 'film_id', 'planet_id');
    }

    public function species(): BelongsToMany
    {
        return $this->belongsToMany(Specie::class, 'swapi_film_specie', 'film_id', 'specie_id');
    }

    public function starships(): BelongsToMany
    {
        return $this->belongsToMany(Starship::class, 'swapi_film_starship', 'film_id', 'starship_id');
    }

    public function vehicles(): BelongsToMany
    {
        return $this->belongsToMany(Vehicle::class, 'swapi_film_vehicle', 'film_id', 'vehicle_id');
    }
}
