<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class PokemonService
{
    public const BASE_URL = 'https://pokeapi.co/api/v2/';

    private function getClient(): PendingRequest
    {
        return Http::baseUrl(self::BASE_URL);
    }

    public function getEvolutionChain(string $name): null|Collection
    {
        $response = $this->getClient()->get('pokemon-species/' . $name);
        $speciesData = $response->object();

        if ($speciesData == null) {
            return null;
        }

        $evolutionChainResponse = $this->getClient()->get($speciesData->evolution_chain->url);
        $evolutionChainData = $evolutionChainResponse->json();

        $evolutions = $this->parseEvolutionChain($evolutionChainData['chain']);

        return $evolutions->map(function ($evolution) {
            return [
                'id' => $evolution['id'],
                'name' => $evolution['name'],
            ];
        });
    }

    private function parseEvolutionChain(array $chain): Collection
    {
        $speciesName = $chain['species']['name'];
        $speciesId = basename(parse_url($chain['species']['url'], PHP_URL_PATH));

        $evolutions = collect([
            [
                'id' => $speciesId,
                'name' => $speciesName,
            ],
        ]);

        if (isset($chain['evolves_to']) && count($chain['evolves_to']) > 0) {
            foreach ($chain['evolves_to'] as $evolution) {
                $evolutions = $evolutions->merge($this->parseEvolutionChain($evolution));
            }
        }

        return $evolutions;
    }

    public function searchByPokemonName(string $name): Collection
    {
        $pokemons = $this->getList(['limit' => 10000]);

        return $pokemons->filter(function ($pokemon) use ($name) {
            return stripos($pokemon['name'], $name) !== false;
        });
    }

    public function getList(array $url = null): Collection
    {
        $response = $this->getClient()->get('pokemon/', $url);

        $pokemons = $response->collect('results');

        return $pokemons->map(function ($pokemon) {
            return [
                'id' => basename(parse_url($pokemon['url'], PHP_URL_PATH)),
                'name' => $pokemon['name'],
            ];
        });
    }

    public function getByName(string $name, int $maxDescriptions = null): Collection
    {
        // Get Pokemon
        $response = $this->getClient()->get('pokemon/' . $name);
        $pokemon = $response->object();

        // Damage Relations
        $types = collect($pokemon->types);
        $damageRelations = $types->map(function ($type) {
            $typeResponse = $this->getClient()->get('type/' . $type->type->name);
            $typeData = $typeResponse->object();
            $damageRelations = $typeData->damage_relations;

            return [
                'noDamageTo' => $damageRelations->no_damage_to,
                'noDamageFrom' => $damageRelations->no_damage_from,
                'halfDamageTo' => $damageRelations->half_damage_to,
                'halfDamageFrom' => $damageRelations->half_damage_from,
                'doubleDamageTo' => $damageRelations->double_damage_to,
                'doubleDamageFrom' => $damageRelations->double_damage_from,
            ];
        })->collapse();

        // Pokemon Stats
        $stats = collect($pokemon->stats);

        $stats = $stats->reduce(function ($carry, $stat) {
            $statName = strtolower($stat->stat->name);
            $statValue = $stat->base_stat;

            return $carry->merge([$statName => $statValue]);
        }, collect());

        // Pokemon Abilities
        $abilities = collect($pokemon->abilities);

        $abilityNames = $abilities->map(function ($ability) {
            return ucfirst($ability->ability->name);
        });

        // Pokemon Descriptions
        $speciesResponse = $this->getClient()->get('pokemon-species/' . $pokemon->species->name);
        $speciesData = $speciesResponse->object();

        $flavorTextEntries = collect($speciesData->flavor_text_entries);

        $descriptions = $flavorTextEntries->reduce(function ($carry, $text) use ($maxDescriptions) {
            if ($text->language->name === 'en' && count($carry) < $maxDescriptions) {
                $carry[] = str_replace(["\n", "\f"], ' ', ucfirst($text->flavor_text));
            }

            return $carry;
        }, collect());

        // Return Collection
        return collect([
            'id' => $pokemon->id,
            'name' => $pokemon->name,
            'descriptions' => $descriptions->toArray(),
            'height' => $pokemon->height / 10,
            'weight' => $pokemon->weight / 10,
            'abilities' => $abilityNames->toArray(),
            'stats' => $stats->toArray(),
            'damageRelations' => $damageRelations->toArray(),
            'images' => [
                'frontImg' => $pokemon->sprites->front_default,
                'backImg' => $pokemon->sprites->back_default,
                'artwork' => $pokemon->sprites->other->{'official-artwork'}->front_default,
            ],
            'types' => $types->toArray(),
        ]);
    }

    public function getTypes(): Collection
    {
        $response = $this->getClient()->get('type/');

        $result = $response->collect('results');

        return $result->slice(0, $result->count() - 2);
    }

    public function getByType(string $type, $perPage = 20, array $url = null): LengthAwarePaginator
    {
        $response = $this->getClient()->get('type/' . $type, $url);
        $pokemons = $response->collect('pokemon');

        $pokemonList = $pokemons->map(function ($pokemon) {
            $pokemonUrl = $pokemon['pokemon']['url'];
            $pokemonId = basename(parse_url($pokemonUrl, PHP_URL_PATH));

            return [
                'id' => $pokemonId,
                'name' => $pokemon['pokemon']['name'],
            ];
        });

        $currentPage = request()->get('page', 1);

        return new LengthAwarePaginator(
            $pokemonList->forPage($currentPage, $perPage),
            $pokemonList->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url()]
        );
    }
}
