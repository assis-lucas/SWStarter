<?php

namespace App\Client\Swapi\Providers;

use Illuminate\Support\Facades\Http;

class EntityProvider implements EntityProviderInterface
{
    public function __construct(private string $baseUri, private string $endpoint)
    {
    }

    public function get(int $page = 1): array
    {
        $response = Http::acceptJson()->get("$this->baseUri/$this->endpoint/", ['page' => $page]);

        return $response->json();
    }

    public function find(int $id): array
    {
        $response = Http::acceptJson()->get("$this->baseUri/$this->endpoint/$id");

        return $response->json();
    }
}
