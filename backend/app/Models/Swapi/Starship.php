<?php

namespace App\Models\Swapi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Starship extends Model
{
    use HasFactory;

    protected $table = 'swapi_starships';

    protected $fillable = [
        'swapi_id',
        'name',
        'model',
        'manufacturer',
        'cost_in_credits',
        'length',
        'max_atmosphering_speed',
        'crew',
        'passengers',
        'cargo_capacity',
        'consumables',
        'hyperdrive_rating',
        'MGLT',
        'starship_class'
    ];

    public function pilots(): BelongsToMany
    {
        return $this->belongsToMany(Person::class, 'swapi_person_starship', 'starship_id', 'person_id');
    }

    public function films(): BelongsToMany
    {
        return $this->belongsToMany(Film::class, 'swapi_film_starship', 'starship_id', 'film_id');
    }
}
