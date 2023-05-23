<?php

declare(strict_types=1);

use App\Services\PokemonService;
use Illuminate\Http\Client\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Http;

it(
    'can search by a pokemon name on the right url and returns only the matching pokemons',
    function (): void {
        Http::fake([
            'https://pokeapi.co/api/v2/pokemon/*' => Http::response(
                [
                    'results' => [
                        [
                            'name' => 'bulbasaur',
                            'url' => 'https://pokeapi.co/api/v2/pokemon/1/',
                        ],
                        [
                            'name' => 'ivysaur',
                            'url' => 'https://pokeapi.co/api/v2/pokemon/2/',
                        ],
                        [
                            'name' => 'pikachu',
                            'url' => 'https://pokeapi.co/api/v2/pokemon/25/',
                        ],
                    ],
                ],
                200,
                ['Content-Type' => 'application/json'],
            ),
        ]);

        $result = app(PokemonService::class)->searchByPokemonName('saur');

        Http::assertSent(
            fn (Request $request): bool => $request->url() ==
                'https://pokeapi.co/api/v2/pokemon/?limit=10000',
        );

        expect($result->toArray())->toBe([
            [
                'id' => '1',
                'name' => 'bulbasaur',
            ],
            [
                'id' => '2',
                'name' => 'ivysaur',
            ],
        ]);
    },
);

it(
    'can get all types from the pokeAPI without the useless two types',
    function (): void {
        Http::fake([
            'https://pokeapi.co/api/v2/type/' => Http::response(
                [
                    'results' => [
                        [
                            'name' => 'normal',
                            'url' => 'https://pokeapi.co/api/v2/type/1/',
                        ],
                        [
                            'name' => 'fighting',
                            'url' => 'https://pokeapi.co/api/v2/type/2/',
                        ],
                        [
                            'name' => 'unknown',
                            'url' => 'https://pokeapi.co/api/v2/type/10001/',
                        ],
                        [
                            'name' => 'shadow',
                            'url' => 'https://pokeapi.co/api/v2/type/10002/',
                        ],
                    ],
                ],
                200,
                ['Content-Type' => 'application/json'],
            ),
        ]);

        $result = app(PokemonService::class)->getTypes();

        Http::assertSent(
            fn (Request $request): bool => $request->url() ==
                'https://pokeapi.co/api/v2/type/',
        );

        expect($result->toArray())->toBe([
            [
                'name' => 'normal',
                'url' => 'https://pokeapi.co/api/v2/type/1/',
            ],
            [
                'name' => 'fighting',
                'url' => 'https://pokeapi.co/api/v2/type/2/',
            ],
        ]);
    },
);

it('can return a paginated object of the pokemon of one type', function (): void {
    Http::fake([
        'https://pokeapi.co/api/v2/type/normal' => Http::response(
            [
                'pokemon' => [
                    [
                        'pokemon' => [
                            'name' => 'pidgey',
                            'url' => 'https://pokeapi.co/api/v2/pokemon/16/',
                        ],
                        'slot' => 1,
                    ],
                    [
                        'pokemon' => [
                            'name' => 'pidgeotto',
                            'url' => 'https://pokeapi.co/api/v2/pokemon/17/',
                        ],
                        'slot' => 1,
                    ],
                    [
                        'pokemon' => [
                            'name' => 'lickitung',
                            'url' => 'https://pokeapi.co/api/v2/pokemon/108/',
                        ],
                        'slot' => 1,
                    ],
                    [
                        'pokemon' => [
                            'name' => 'ursaring',
                            'url' => 'https://pokeapi.co/api/v2/pokemon/217/',
                        ],
                        'slot' => 1,
                    ],
                ],
            ],
            200,
            ['Content-Type' => 'application/json'],
        ),
    ]);

    $result = app(PokemonService::class)->getByType('normal');

    expect($result)
        ->toBeInstanceOf(LengthAwarePaginator::class)
        ->toHaveCount(4);
});

it('can return an array with the details from a single pokemon', function (): void {
    Http::fake([
        'https://pokeapi.co/api/v2/pokemon/pidgeotto' => Http::response(
            [
                'id' => 17,
                'name' => 'pidgeotto',
                'height' => '11',
                'weight' => 300,
                'species' => [
                    'name' => 'pidgeotto',
                    'url' => 'https://pokeapi.co/api/v2/pokemon-species/17/',
                ],
                'sprites' => [
                    'back_default' => 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/back/17.png',
                    'front_default' => 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/17.png',
                    'other' => [
                        'official-artwork' => [
                            'front_default' => 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/official-artwork/17.png',
                        ],
                    ],
                ],
                'types' => [
                    [
                        'type' => [
                            'name' => 'normal',
                            'url' => 'https://pokeapi.co/api/v2/type/1/',
                        ],
                        'slot' => 1,
                    ],
                    [
                        'type' => [
                            'name' => 'flying',
                            'url' => 'https://pokeapi.co/api/v2/type/3/',
                        ],
                        'slot' => 2,
                    ],
                ],
                'stats' => [
                    [
                        'stat' => [
                            'name' => 'hp',
                            'url' => 'https://pokeapi.co/api/v2/stat/1/',
                        ],
                        'base_stat' => 63,
                        'effort' => 0,
                    ],
                    [
                        'stat' => [
                            'name' => 'attack',
                            'url' => 'https://pokeapi.co/api/v2/stat/2/',
                        ],
                        'base_stat' => 60,
                        'effort' => 0,
                    ],
                    [
                        'stat' => [
                            'name' => 'defense',
                            'url' => 'https://pokeapi.co/api/v2/stat/3/',
                        ],
                        'base_stat' => 55,
                        'effort' => 0,
                    ],
                    [
                        'stat' => [
                            'name' => 'special-attack',
                            'url' => 'https://pokeapi.co/api/v2/stat/4/',
                        ],
                        'base_stat' => 50,
                        'effort' => 0,
                    ],
                    [
                        'stat' => [
                            'name' => 'special-defense',
                            'url' => 'https://pokeapi.co/api/v2/stat/5/',
                        ],
                        'base_stat' => 50,
                        'effort' => 0,
                    ],
                    [
                        'stat' => [
                            'name' => 'speed',
                            'url' => 'https://pokeapi.co/api/v2/stat/6/',
                        ],
                        'base_stat' => 71,
                        'effort' => 2,
                    ],
                ],
                'abilities' => [
                    [
                        'ability' => [
                            'name' => 'keen-eye',
                            'url' => 'https://pokeapi.co/api/v2/ability/51/',
                        ],
                        'slot' => 1,
                    ],
                    [
                        'ability' => [
                            'name' => 'tangled-feet',
                            'url' => 'https://pokeapi.co/api/v2/ability/77/',
                        ],
                        'slot' => 2,
                    ],
                ],
            ],
            200,
            ['Content-Type' => 'application/json'],
        ),
        'https://pokeapi.co/api/v2/type/normal' => Http::response(
            [
                'damage_relations' => [
                    'double_damage_from' => [
                        [
                            'name' => 'fighting',
                            'url' => 'https://pokeapi.co/api/v2/type/2/',
                        ],
                    ],
                    'double_damage_to' => [],
                    'half_damage_from' => [],
                    'half_damage_to' => [
                        [
                            'name' => 'rock',
                            'url' => 'https://pokeapi.co/api/v2/type/6/',
                        ],
                        [
                            'name' => 'steel',
                            'url' => 'https://pokeapi.co/api/v2/type/9/',
                        ],
                    ],
                    'no_damage_from' => [
                        [
                            'name' => 'ghost',
                            'url' => 'https://pokeapi.co/api/v2/type/8/',
                        ],
                    ],
                    'no_damage_to' => [
                        [
                            'name' => 'ghost',
                            'url' => 'https://pokeapi.co/api/v2/type/8/',
                        ],
                    ],
                ],
            ],
            200,
            ['Content-Type' => 'application/json'],
        ),
        'https://pokeapi.co/api/v2/type/pokemon-species/pidgeotto' => Http::response(
            [
                'flavor_text_entries' => [
                    [
                        'flavor_text' => 'Very protective of its sprawling territorial area,this POKéMON will fiercely peck at any intruder.',
                        [
                            'language' => ['name' => 'en'],
                            'url' => 'https://pokeapi.co/api/v2/language/9/',
                        ],
                    ],
                ],
            ],
            200,
            ['Content-Type' => 'application/json'],
        ),
    ]);

    $result = app(PokemonService::class)->getByName('pidgeotto', 1);

    expect($result->get('id'))->toBe(17)
        ->and($result->get('name'))->toBe('pidgeotto')
        ->and($result->get('descriptions'))->toBe([
            0 => 'Very protective of its sprawling territorial area, this POKéMON will fiercely peck at any intruder.',
        ]);
});

it('can create the evolution-chain for a pokemon', function (): void {
    Http::fake(
        [
            'https://pokeapi.co/api/v2/pokemon-species/*' => Http::response(
                [
                    'evolution_chain' => [
                        'url' => 'https://pokeapi.co/api/v2/evolution-chain/10/',
                    ],
                ],
                200,
                ['Content-Type' => 'application/json'],
            ),
            'https://pokeapi.co/api/v2/evolution-chain/*' => Http::response(
                [
                    'chain' => [
                        'evolves_to' => [
                            [
                                'evolves_to' => [
                                    [
                                        'evolves_to' => [],
                                        'species' => [
                                            'name' => 'raichu',
                                            'url' => 'https://pokeapi.co/api/v2/pokemon-species/26/',
                                        ],
                                    ],
                                ],
                                'species' => [
                                    'name' => 'pikachu',
                                    'url' => 'https://pokeapi.co/api/v2/pokemon-species/25/',
                                ],
                            ],
                        ],
                        'species' => [
                            'name' => 'pichu',
                            'url' => 'https://pokeapi.co/api/v2/pokemon-species/172/',
                        ],
                    ],
                ],
                200,
                ['Content-Type' => 'application/json'],
            ),
        ],
    );

    $result = app(PokemonService::class)->getEvolutionChain('pikachu');

    expect($result->toArray())->toBe([
        [
            'id' => '172',
            'name' => 'pichu',
        ],
        [
            'id' => '25',
            'name' => 'pikachu',
        ],
        [
            'id' => '26',
            'name' => 'raichu',
        ],
    ]);
});

it('can return null if there is no evolution', function (): void {
    Http::fake(
        [
            'https://pokeapi.co/api/v2/pokemon-species/*' => Http::response(
                [],
                200,
                ['Content-Type' => 'application/json'],
            ),
        ],
    );

    $result = app(PokemonService::class)->getEvolutionChain('charizard-gmax');

    expect($result)->toBeNull();
});
