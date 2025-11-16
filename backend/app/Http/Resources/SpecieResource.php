<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SpecieResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'swapi_id' => $this->swapi_id,
            'name' => $this->name,
            'classification' => $this->classification,
            'designation' => $this->designation,
            'average_height' => $this->average_height,
            'skin_colors' => $this->skin_colors,
            'hair_colors' => $this->hair_colors,
            'eye_colors' => $this->eye_colors,
            'average_lifespan' => $this->average_lifespan,
            'language' => $this->language,
            'homeworld' => new PlanetResource($this->whenLoaded('homeworld')),
            'people' => PersonResource::collection($this->whenLoaded('people')),
            'films' => FilmResource::collection($this->whenLoaded('films')),
        ];
    }
}
