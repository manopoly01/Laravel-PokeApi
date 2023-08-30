<?php

declare(strict_types=1);

use App\Http\Livewire\ShowPokemon;
use App\Services\PokemonService;
use Livewire\Livewire;
use Mockery\MockInterface;

it('can show a single pokemon with all stats and evolutions', function (): void {
    $pokemon = collect([
        'id' => 16,
        'name' => 'pidgey',
        'descriptions' => ['A common sight in forests and woods. It flaps its wings at ground level to kick up blinding sand.'],
        'height' => 11.5 / 10,
        'weight' => 130.7 / 10,
        'abilities' => ['keen-eye', 'tangled-feet'],
        'stats' => [
            'hp' => 40,
            'attack' => 45,
            'defense' => 40,
            'special-attack' => 35,
            'special-defense' => 35,
            'speed' => 56,
        ],
        'damageRelations' => [
            'noDamageTo' => [(object) ['name' => 'ghost']],
            'noDamageFrom' => [(object) ['name' => 'ghost'], (object) ['name' => 'ground']],
            'halfDamageTo' => [(object) ['name' => 'rock'], (object) ['name' => 'steel']],
            'halfDamageFrom' => [(object) ['name' => 'fighting'], (object) ['name' => 'bug']],
            'doubleDamageTo' => [(object) ['name' => 'bug'], (object) ['name' => 'grass']],
            'doubleDamageFrom' => [(object) ['name' => 'electric'], (object) ['name' => 'ice']],
        ],
        'images' => [
            'frontImg' => 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/16.png',
            'backImg' => 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/back/16.png',
            'artwork' => 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/official-artwork/16.png',
        ],
        'types' => [(object) ['type' => (object) ['name' => 'normal']], (object) ['type' => (object) ['name' => 'flying']]],
    ]);

    $evolutions = collect([
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

    $this->mock(PokemonService::class, function (MockInterface $mock) use ($pokemon, $evolutions): void {
        $mock->shouldReceive('getByName')->once()->andReturn($pokemon);
        $mock->shouldReceive('getEvolutionChain')->once()->andReturn($evolutions);
    });

    Livewire::test(ShowPokemon::class, ['pidgey'])
        ->assertSeeInOrder([
            'Pidgey',
            '16',
            '40',
            'normal',
            'flying',
            'A common sight in forests and woods. It flaps its wings at ground level to kick up blinding sand.',
            'keen-eye',
            'tangled-feet',
            '40',
            '45',
            '40',
            '35',
            '35',
            '56',
            'ghost',
            'rock',
            'steel',
            'bug',
            'grass',
            'ghost',
            'ground',
            'fighting',
            'bug',
            'electric',
            'ice',
            'Evolutions',
            'pichu',
            '172',
            'pikachu',
            '25',
            'raichu',
            '26',
        ]);
});
