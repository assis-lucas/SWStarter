<?php

namespace App\Client\Swapi;

use App\Client\Swapi\Providers\EntityProvider;

class SwapiLiveClient
{
    private static string $baseUri = 'https://swapi.dev/api';

    public static function planet(): EntityProvider
    {
        return new EntityProvider(baseUri: self::$baseUri, endpoint: 'planets');
    }

    public static function film(): EntityProvider
    {
        return new EntityProvider(baseUri: self::$baseUri, endpoint: 'films');
    }

    public static function people(): EntityProvider
    {
        return new EntityProvider(baseUri: self::$baseUri, endpoint: 'people');
    }

    public static function species(): EntityProvider
    {
        return new EntityProvider(baseUri: self::$baseUri, endpoint: 'species');
    }

    public static function starship(): EntityProvider
    {
        return new EntityProvider(baseUri: self::$baseUri, endpoint: 'starships');
    }

    public static function vehicle(): EntityProvider
    {
        return new EntityProvider(baseUri: self::$baseUri, endpoint: 'vehicles');
    }
}
