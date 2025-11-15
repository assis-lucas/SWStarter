<?php

namespace App\Client\Swapi\Providers;

interface EntityProviderInterface
{
    public function get(int $page = 1): array;

    public function find(int $id): array;
}
