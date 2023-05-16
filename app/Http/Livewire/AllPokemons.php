<?php

declare(strict_types=1);

namespace App\Http\Livewire;

use App\Services\PokemonService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\Factory;
use Illuminate\View\View;
use Livewire\Component;

class AllPokemons extends Component
{
    private LengthAwarePaginator|null $pokemonList = null;

    public ?string $type = null;

    public function mount(string $name, PokemonService $pokemonService): void
    {
        $this->pokemonList = $pokemonService->getByType($name);
        $this->type = $name;
    }

    public function render(): View|Factory
    {
        return view('livewire.all-pokemons', [
            'pokemonList' => $this->pokemonList,
        ])->layout('components.layout');
    }
}
