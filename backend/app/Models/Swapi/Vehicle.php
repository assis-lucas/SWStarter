<?php

namespace App\Models\Swapi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Vehicle extends Model
{
    use HasFactory;

    protected $table = 'swapi_vehicles';

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
        'vehicle_class'
    ];

    public function pilots(): BelongsToMany
    {
        return $this->belongsToMany(Person::class, 'swapi_person_vehicle', 'vehicle_id', 'person_id');
    }

    public function films(): BelongsToMany
    {
        return $this->belongsToMany(Film::class, 'swapi_film_vehicle', 'vehicle_id', 'film_id');
    }
}
