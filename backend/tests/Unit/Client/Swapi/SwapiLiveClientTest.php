<?php

namespace Tests\Unit\Client\Swapi;

use Generator;
use PHPUnit\Framework\TestCase;
use App\Client\Swapi\SwapiLiveClient;
use App\Client\Swapi\Providers\EntityProvider;
use PHPUnit\Framework\Attributes\DataProvider;

class SwapiLiveClientTest extends TestCase
{
    #[DataProvider('entityMethodProvider')]
    public function test_entity_method_returns_entity_provider(string $method): void
    {
        $provider = SwapiLiveClient::$method();

        $this->assertInstanceOf(EntityProvider::class, $provider);
    }

    public static function entityMethodProvider(): Generator
    {
        yield 'planet' => ['planet'];
        yield 'film' => ['film'];
        yield 'people' => ['people'];
        yield 'species' => ['species'];
        yield 'starship' => ['starship'];
        yield 'vehicle' => ['vehicle'];
    }
}
