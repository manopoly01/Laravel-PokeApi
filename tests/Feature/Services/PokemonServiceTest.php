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

        expect($result)->toBe([
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

        expect($result)->toBe([
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

    expect($result)->toBe([
        'id' => 17,
        'name' => 'pidgeotto',
        'descriptions' => [
            0 => 'Very protective of its sprawling territorial area, this POKéMON will fiercely peck at any intruder.',
        ],
        'height' => 1.1,
        'weight' => 30,
        'abilities' => [
            0 => 'Keen-eye',
            1 => 'Tangled-feet',
        ],
        'hp' => 63,
        'attack' => 60,
        'defense' => 55,
        'specialAttack' => 50,
        'specialDefense' => 50,
        'speed' => 71,
        'noDamageTo' => [
            0 => [
                'name' => 'ghost',
                'url' => 'https://pokeapi.co/api/v2/type/8/',
            ],
        ],
        'noDamageFrom' => [
            0 => [
                'name' => 'ghost',
                'url' => 'https://pokeapi.co/api/v2/type/8/',
            ],
            1 => [
                'name' => 'ground',
                'url' => 'https://pokeapi.co/api/v2/type/5/',
            ],
        ],
        'halfDamageTo' => [
            0 => [
                'name' => 'rock',
                'url' => 'https://pokeapi.co/api/v2/type/6/',
            ],
            1 => [
                'name' => 'steel',
                'url' => 'https://pokeapi.co/api/v2/type/9/',
            ],
            2 => [
                'name' => 'rock',
                'url' => 'https://pokeapi.co/api/v2/type/6/',
            ],
            3 => [
                'name' => 'steel',
                'url' => 'https://pokeapi.co/api/v2/type/9/',
            ],
            4 => [
                'name' => 'electric',
                'url' => 'https://pokeapi.co/api/v2/type/13/',
            ],
        ],
        'halfDamageFrom' => [
            0 => [
                'name' => 'fighting',
                'url' => 'https://pokeapi.co/api/v2/type/2/',
            ],
            1 => [
                'name' => 'bug',
                'url' => 'https://pokeapi.co/api/v2/type/7/',
            ],
            2 => [
                'name' => 'grass',
                'url' => 'https://pokeapi.co/api/v2/type/12/',
            ],
        ],
        'doubleDamageTo' => [
            0 => [
                'name' => 'fighting',
                'url' => 'https://pokeapi.co/api/v2/type/2/',
            ],
            1 => [
                'name' => 'bug',
                'url' => 'https://pokeapi.co/api/v2/type/7/',
            ],
            2 => [
                'name' => 'grass',
                'url' => 'https://pokeapi.co/api/v2/type/12/',
            ],
        ],
        'doubleDamageFrom' => [
            0 => [
                'name' => 'fighting',
                'url' => 'https://pokeapi.co/api/v2/type/2/',
            ],
            1 => [
                'name' => 'rock',
                'url' => 'https://pokeapi.co/api/v2/type/6/',
            ],
            2 => [
                'name' => 'electric',
                'url' => 'https://pokeapi.co/api/v2/type/13/',
            ],
            3 => [
                'name' => 'ice',
                'url' => 'https://pokeapi.co/api/v2/type/15/',
            ],
        ],
        'frontImg' => 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/17.png',
        'backImg' => 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/back/17.png',
        'artwork' => 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/official-artwork/17.png',
        'types' => [
            0 => [
                'type' => [
                    'name' => 'normal',
                    'url' => 'https://pokeapi.co/api/v2/type/1/',
                ],
                'slot' => 1,
            ],
            1 => [
                'type' => [
                    'name' => 'flying',
                    'url' => 'https://pokeapi.co/api/v2/type/3/',
                ],
                'slot' => 2,
            ],
        ],
    ]);
});
