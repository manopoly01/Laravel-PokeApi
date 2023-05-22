<?php

use App\Http\Livewire\SearchPokemon;
use App\Services\PokemonService;
use Livewire\Livewire;
use Mockery\MockInterface;

it('can search a pokemon and view all results', function () {
    $pokemonList = [
        [
            'id' => '1',
            'name' => 'bulbasaur'
        ],
        [
            'id' => '2',
            'name' => 'ivysaur'
        ],
        [
            'id' => '3',
            'name' => 'venusaur'
        ],
    ];

    $this->mock(PokemonService::class, function (MockInterface $mock) use ($pokemonList) {
        $mock->shouldReceive('searchByPokemonName')->once()->andReturn($pokemonList);
    });

    Livewire::test(SearchPokemon::class, ['saur'])
        ->set('name', 'saur')
        ->assertSeeInOrder(['bulbasaur', 'ivysaur', 'venusaur'])
        ->assertSeeInOrder(['1', '2', '3']);
});
