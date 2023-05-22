<?php

declare(strict_types=1);

use App\Http\Livewire\AllPokemons;
use App\Services\PokemonService;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Livewire;
use Mockery\MockInterface;

it('can show all pokemon of a specific type', function (): void {
    $pokemonCollection = collect([
        [
            'id' => 16,
            'name' => 'pidgey',
        ],
        [
            'id' => 39,
            'name' => 'jigglypuff',
        ],
        [
            'id' => 52,
            'name' => 'meowth',
        ],
    ]);

    $pokemon = new LengthAwarePaginator(
        $pokemonCollection->forPage(1, 20),
        $pokemonCollection->count(),
        20,
        1,
        ['path' => request()->url()]
    );

    $this->mock(PokemonService::class, function (MockInterface $mock) use ($pokemon): void {
        $mock->shouldReceive('getByType')->once()->andReturn($pokemon);
    });

    Livewire::test(AllPokemons::class, ['normal'])
        ->assertSeeInOrder(['pidgey', 'jigglypuff', 'meowth'])
        ->assertSeeInOrder(['16', '39', '52'])
        ->assertSee(['bg-pokemon-normal-bg'])
        ->assertSee(['text-pokemon-normal-text']);
});
