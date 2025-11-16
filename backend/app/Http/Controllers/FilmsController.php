<?php

namespace App\Http\Controllers;

use App\Models\Swapi\Film;
use App\Http\Requests\FilmRequest;
use App\Http\Resources\FilmResource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class FilmsController extends Controller
{
    public function index(FilmRequest $request): AnonymousResourceCollection
    {
        $films = Film::with(['characters', 'planets', 'starships', 'vehicles', 'species'])
            ->when(
                $request->search,
                fn (Builder $query): Builder => $query->where('title', 'like', "%{$request->search}%")
                    ->orWhere('opening_crawl', 'like', "%{$request->search}%")
            )
            ->paginate(10);

        return FilmResource::collection($films);
    }

    public function show(Film $film): FilmResource
    {
        $film->loadMissing(['characters', 'planets', 'starships', 'vehicles', 'species']);
        return new FilmResource($film);
    }
}
