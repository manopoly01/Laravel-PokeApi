<?php

declare(strict_types=1);

use App\Http\Livewire\AllTypes;
use App\Services\PokemonService;
use Livewire\Livewire;
use Mockery\MockInterface;

it('can show all the types on the page', function (): void {
    $typesArray = [
        [
            'name' => 'normal',
            'url' => 'https://pokeapi.co/api/v2/type/1/',
        ],
        [
            'name' => 'fighting',
            'url' => 'https://pokeapi.co/api/v2/type/2/',
        ],
        [
            'name' => 'flying',
            'url' => 'https://pokeapi.co/api/v2/type/3/',
        ],
        [
            'name' => 'poison',
            'url' => 'https://pokeapi.co/api/v2/type/4/',
        ],
    ];

    $this->mock(PokemonService::class, function (MockInterface $mock) use ($typesArray): void {
        $mock->shouldReceive('getTypes')->once()->andReturn($typesArray);
    });

    Livewire::test(AllTypes::class)
        ->assertSeeInOrder(['normal', 'fighting', 'flying', 'poison'])
        ->assertSee(['bg-pokemon-normal-bg', 'bg-pokemon-fighting-bg', 'bg-pokemon-flying-bg', 'bg-pokemon-poison-bg'])
        ->assertSee(['text-pokemon-normal-text', 'text-pokemon-fighting-text', 'text-pokemon-flying-text', 'text-pokemon-poison-text']);
});
