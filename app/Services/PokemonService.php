<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Http;

class PokemonService
{
    public const BASE_URL = 'https://pokeapi.co/api/v2/';

    private function getClient(): PendingRequest
    {
        return Http::baseUrl(self::BASE_URL);
    }

    public function searchPokemonName(string $name): array
    {
        $response = $this->getClient()->get('pokemon/' . $name);

        return $response->collect()->toArray();
    }

    public function getList(array $url = null): array
    {
        $response = $this->getClient()->get('pokemon/', $url);
        $pokemons = $response->collect('results')->toArray();

        $nextPageArray = null;
        $nextPageString = $response->object()->next;
        if ($nextPageString) {
            $nextPageArray = $this->getPageArray($nextPageString);
        }

        $previousPageArray = null;
        $previousPageString = $response->object()->previous;
        if ($previousPageString) {
            $previousPageArray = $this->getPageArray($previousPageString);
        }

        $pokemonList = [];
        foreach ($pokemons as $pokemon) {
            $pokemonList[] = [
                'id' => basename(parse_url($pokemon['url'], PHP_URL_PATH)),
                'name' => $pokemon['name'],
            ];
        }

        return [
            'pokemonList' => $pokemonList,
            'nextPageArray' => $nextPageArray,
            'previousPageArray' => $previousPageArray,
        ];
    }

    public function getByName(string $name): array
    {
        $response = $this->getClient()->get('pokemon/' . $name);
        $pokemon = $response->collect()->toArray();

        return [
            'name' => $pokemon['name'],
            'height' => $pokemon['height'] / 10,
            'weight' => $pokemon['weight'] / 10,
            'frontImg' => $pokemon['sprites']['front_default'],
            'backImg' => $pokemon['sprites']['back_default'],
            'artwork' => $pokemon['sprites']['other']['official-artwork']['front_default'],
            'types' => $pokemon['types'],
        ];
    }

    public function getTypes(): array
    {
        $response = $this->getClient()->get('type/');

        $result = $response->collect()->toArray()['results'];

        return array_slice($result, 0, -2);
    }

    public function getByType(string $type, $perPage = 20, array $url = null): LengthAwarePaginator
    {
        $response = $this->getClient()->get('type/' . $type, $url);
        $pokemons = $response->collect('pokemon')->toArray();

        $pokemonList = [];
        foreach ($pokemons as $pokemon) {
            $pokemonUrl = $pokemon['pokemon']['url'];
            $pokemonId = basename(parse_url($pokemonUrl, PHP_URL_PATH));

            $pokemonList[] = [
                'id' => $pokemonId,
                'name' => $pokemon['pokemon']['name'],
            ];
        }

        $currentPage = request()->get('page', 1);

        $pokemonCollection = collect($pokemonList);

        return new LengthAwarePaginator(
            $pokemonCollection->forPage($currentPage, $perPage),
            $pokemonCollection->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url()]
        );
    }

    public function getPageArray(string $pageUrl): array
    {
        $parts = parse_url($pageUrl);

        parse_str($parts['query'], $query);

        return [
            'offset' => isset($query['offset']) ? intval($query['offset']) : 0,
            'limit' => isset($query['limit']) ? intval($query['limit']) : 0,
        ];
    }
}
