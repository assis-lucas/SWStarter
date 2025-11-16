<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FilmResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'swapi_id' => $this->swapi_id,
            'title' => $this->title,
            'episode_id' => $this->episode_id,
            'opening_crawl' => $this->opening_crawl,
            'director' => $this->director,
            'producer' => $this->producer,
            'release_date' => $this->release_date,
            'characters' => PersonResource::collection($this->whenLoaded('characters')),
            'planets' => PlanetResource::collection($this->whenLoaded('planets')),
            'starships' => StarshipResource::collection($this->whenLoaded('starships')),
            'vehicles' => VehicleResource::collection($this->whenLoaded('vehicles')),
            'species' => SpecieResource::collection($this->whenLoaded('species')),
        ];
    }
}
