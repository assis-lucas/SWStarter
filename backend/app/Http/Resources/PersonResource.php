<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PersonResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'swapi_id' => $this->swapi_id,
            'name' => $this->name,
            'height' => $this->height,
            'mass' => $this->mass,
            'hair_color' => $this->hair_color,
            'skin_color' => $this->skin_color,
            'eye_color' => $this->eye_color,
            'birth_year' => $this->birth_year,
            'gender' => $this->gender,
            'homeworld' => new PlanetResource($this->whenLoaded('homeworld')),
            'films' => FilmResource::collection($this->whenLoaded('films')),
            'species' => SpecieResource::collection($this->whenLoaded('species')),
            'starships' => StarshipResource::collection($this->whenLoaded('starships')),
            'vehicles' => VehicleResource::collection($this->whenLoaded('vehicles')),
        ];
    }
}
