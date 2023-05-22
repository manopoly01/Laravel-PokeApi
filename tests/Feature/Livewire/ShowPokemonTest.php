<?php

declare(strict_types=1);

use App\Http\Livewire\ShowPokemon;
use App\Services\PokemonService;
use Livewire\Livewire;
use Mockery\MockInterface;

it('can show a single pokemon with all stats', function (): void {
    $pokemon = [
        'id' => 16,
        'name' => 'pidgey',
        'descriptions' => ['A common sight in forests and woods. It flaps its wings at ground level to kick up blinding sand.'],
        'height' => 11.5 / 10,
        'weight' => 130.7 / 10,
        'abilities' => ['keen-eye', 'tangled-feet'],
        'hp' => 40,
        'attack' => 45,
        'defense' => 40,
        'specialAttack' => 35,
        'specialDefense' => 35,
        'speed' => 56,
        'noDamageTo' => [['name' => 'ghost']],
        'noDamageFrom' => [['name' => 'ghost'], ['name' => 'ground']],
        'halfDamageTo' => [['name' => 'rock'], ['name' => 'steel']],
        'halfDamageFrom' => [['name' => 'fighting'], ['name' => 'bug']],
        'doubleDamageTo' => [['name' => 'bug'], ['name' => 'grass']],
        'doubleDamageFrom' => [['name' => 'electric'], ['name' => 'ice']],
        'frontImg' => 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/16.png',
        'backImg' => 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/back/16.png',
        'artwork' => 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/official-artwork/16.png',
        'types' => [['type' => ['name' => 'normal']], ['type' => ['name' => 'flying']]],
    ];

    $this->mock(PokemonService::class, function (MockInterface $mock) use ($pokemon): void {
        $mock->shouldReceive('getByName')->once()->andReturn($pokemon);
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
        ]);
});
