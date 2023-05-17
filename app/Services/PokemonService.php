<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Http;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class PokemonService
{
    public const BASE_URL = 'https://pokeapi.co/api/v2/';

    private function getClient(): PendingRequest
    {
        return Http::baseUrl(self::BASE_URL);
    }

    public function searchByPokemonName(string $name): array
    {
        $pokemons = $this->getList(['limit' => 10000])['pokemonList'];

        return array_filter($pokemons, function ($pokemon) use ($name) {
            return stripos($pokemon['name'], $name) !== false;
        });
    }

    public function getList(array $url = null): array
    {
        $response = $this->getClient()->get('pokemon/', $url);

        $pokemons = $response->collect('results')->toArray();

        $pokemonList = [];
        foreach ($pokemons as $pokemon) {
            $pokemonList[] = [
                'id' => basename(parse_url($pokemon['url'], PHP_URL_PATH)),
                'name' => $pokemon['name'],
            ];
        }

        return [
            'pokemonList' => $pokemonList,
        ];
    }

    public function getByName(string $name, int $maxDescriptions = null): array
    {
        $response = $this->getClient()->get('pokemon/' . $name);
        $pokemon = $response->collect()->toArray();

        $types = $pokemon['types'];
        $noDamageTo = [];
        $noDamageFrom = [];
        $halfDamageTo = [];
        $halfDamageFrom = [];
        $doubleDamageTo = [];
        $doubleDamageFrom = [];

        foreach ($types as $type) {
            $typeResponse = $this->getClient()->get('type/' . $type['type']['name']);
            $typeData = $typeResponse->json();

            $damageRelations = $typeData['damage_relations'];
            $noDamageTo = array_merge($noDamageTo, $damageRelations['no_damage_to']);
            $noDamageFrom = array_merge($noDamageFrom, $damageRelations['no_damage_from']);
            $halfDamageTo = array_merge($halfDamageTo, $damageRelations['half_damage_to']);
            $halfDamageFrom = array_merge($halfDamageFrom, $damageRelations['half_damage_from']);
            $doubleDamageTo = array_merge($doubleDamageTo, $damageRelations['double_damage_to']);
            $doubleDamageFrom = array_merge($doubleDamageFrom, $damageRelations['double_damage_from']);
        }

        $stats = $pokemon['stats'];

        $hp = $attack = $defense = $specialAttack = $specialDefense = $speed = 0;

        foreach ($stats as $stat) {
            $statName = strtolower($stat['stat']['name']);
            $statValue = $stat['base_stat'];

            switch ($statName) {
                case 'hp':
                    $hp = $statValue;
                    break;
                case 'attack':
                    $attack = $statValue;
                    break;
                case 'defense':
                    $defense = $statValue;
                    break;
                case 'special-attack':
                    $specialAttack = $statValue;
                    break;
                case 'special-defense':
                    $specialDefense = $statValue;
                    break;
                case 'speed':
                    $speed = $statValue;
                    break;
            }
        }

        $abilities = $pokemon['abilities'];

        $abilityNames = [];

        foreach ($abilities as $ability) {
            $abilityName = ucfirst($ability['ability']['name']);
            $abilityNames[] = $abilityName;
        }

        $speciesResponse = $this->getClient()->get('pokemon-species/' . $pokemon['species']['name']);
        $speciesData = $speciesResponse->json();

        $flavorTextEntries = $speciesData['flavor_text_entries'];

        $descriptions = [];
        $count = 0;

        foreach ($flavorTextEntries as $entry) {
            if ($entry['language']['name'] === 'en') {
                $description = str_replace(["\n", "\f"], ' ', ucfirst($entry['flavor_text']));
                $descriptions[] = $description;
                $count++;

                if ($maxDescriptions !== null && $count >= $maxDescriptions) {
                    break;
                }
            }
        }

        return [
            'id' => $pokemon['id'],
            'name' => $pokemon['name'],
            'descriptions' => $descriptions,
            'height' => $pokemon['height'] / 10,
            'weight' => $pokemon['weight'] / 10,
            'abilities' => $abilityNames,
            'hp' => $hp,
            'attack' => $attack,
            'defense' => $defense,
            'specialAttack' => $specialAttack,
            'specialDefense' => $specialDefense,
            'speed' => $speed,
            'noDamageTo' => $noDamageTo,
            'noDamageFrom' => $noDamageFrom,
            'halfDamageTo' => $halfDamageTo,
            'halfDamageFrom' => $halfDamageFrom,
            'doubleDamageTo' => $doubleDamageTo,
            'doubleDamageFrom' => $doubleDamageFrom,
            'frontImg' => $pokemon['sprites']['front_default'],
            'backImg' => $pokemon['sprites']['back_default'],
            'artwork' => $pokemon['sprites']['other']['official-artwork']['front_default'],
            'types' => $types,
        ];
    }

    public function getTypes(): array
    {
        $response = $this->getClient()->get('type/');

        $result = $response->collect('results')->toArray();

        return array_slice($result, 0, -2);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
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
}
