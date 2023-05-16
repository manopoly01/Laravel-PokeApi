<?php

declare(strict_types=1);

namespace App\Http\Livewire;

use App\Services\PokemonService;
use Illuminate\View\Factory;
use Illuminate\View\View;
use Livewire\Component;

class SearchPokemon extends Component
{
    public array $searchedPokemon = [];

    public string $name = '';

    protected array $rules = [
        'name' => 'required',
    ];

    public function updatedName(string $name): void
    {
        if ($name) {
            $this->searchPokemon();
        }
    }

    public function searchPokemon(): void
    {
        $pokemonService = app(PokemonService::class);
        $this->searchedPokemon = $pokemonService->searchPokemonName(strtolower($this->name));
    }

    public function render(): View|Factory
    {
        return view('livewire.search-pokemon');
    }
}
