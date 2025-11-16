<?php

namespace App\Models\Swapi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Person extends Model
{
    use HasFactory;

    protected $table = 'swapi_people';

    protected $fillable = [
        'swapi_id',
        'name',
        'height',
        'mass',
        'hair_color',
        'skin_color',
        'eye_color',
        'birth_year',
        'gender',
        'homeworld_id'
    ];

    public function homeworld(): BelongsTo
    {
        return $this->belongsTo(Planet::class, 'homeworld_id');
    }

    public function films(): BelongsToMany
    {
        return $this->belongsToMany(Film::class, 'swapi_film_person', 'person_id', 'film_id');
    }

    public function species(): BelongsToMany
    {
        return $this->belongsToMany(Specie::class, 'swapi_person_species', 'person_id', 'specie_id');
    }

    public function starships(): BelongsToMany
    {
        return $this->belongsToMany(Starship::class, 'swapi_person_starship', 'person_id', 'starship_id');
    }

    public function vehicles(): BelongsToMany
    {
        return $this->belongsToMany(Vehicle::class, 'swapi_person_vehicle', 'person_id', 'vehicle_id');
    }
}
