<?php

namespace App\Http\Controllers;

use App\Models\Swapi\Person;
use App\Http\Requests\PersonRequest;
use App\Http\Resources\PersonResource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PeopleController extends Controller
{
    public function index(PersonRequest $request): AnonymousResourceCollection
    {
        $people = Person::with(['homeworld', 'films', 'species', 'starships', 'vehicles'])
            ->when(
                $request->search,
                fn (Builder $query): Builder => $query->where('name', 'like', "%$request->search%")
            )
            ->paginate(10);

        return PersonResource::collection($people);
    }

    public function show(Person $person): PersonResource
    {
        $person->loadMissing(['homeworld', 'films', 'species', 'starships', 'vehicles']);
        return new PersonResource($person);
    }
}
