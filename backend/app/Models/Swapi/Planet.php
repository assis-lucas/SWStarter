<?php

namespace App\Models\Swapi;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Planet extends Model
{
    protected $table = 'swapi_planets';

    protected $fillable = [
        'swapi_id',
        'name',
        'rotation_period',
        'orbital_period',
        'diameter',
        'climate',
        'gravity',
        'terrain',
        'surface_water',
        'population'
    ];

    public function residents(): HasMany
    {
        return $this->hasMany(Person::class, 'homeworld_id');
    }

    public function films(): BelongsToMany
    {
        return $this->belongsToMany(Film::class, 'swapi_film_planet', 'planet_id', 'film_id');
    }
}
