<?php

namespace App\Models\Swapi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Specie extends Model
{
    use HasFactory;

    protected $table = 'swapi_species';

    protected $fillable = [
        'swapi_id',
        'name',
        'classification',
        'designation',
        'average_height',
        'skin_colors',
        'hair_colors',
        'eye_colors',
        'average_lifespan',
        'language',
        'homeworld_id'
    ];

    public function homeworld(): BelongsTo
    {
        return $this->belongsTo(Planet::class, 'homeworld_id');
    }

    public function people(): BelongsToMany
    {
        return $this->belongsToMany(Person::class, 'swapi_person_species', 'specie_id', 'person_id');
    }

    public function films(): BelongsToMany
    {
        return $this->belongsToMany(Film::class, 'swapi_film_specie', 'specie_id', 'film_id');
    }
}
