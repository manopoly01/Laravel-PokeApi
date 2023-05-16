<?php

declare(strict_types=1);

namespace App\Http\Livewire;

use App\Services\PokemonService;
use Illuminate\View\Factory;
use Illuminate\View\View;
use Livewire\Component;

class ShowPokemon extends Component
{
    public ?array $pokemon = null;
    public ?string $name = null;
    public ?float $height = null;
    public ?float $weight = null;
    public ?string $frontImg = null;
    public ?string $backImg = null;
    public ?string $artwork = null;
    public ?array $types = null;

    public function mount(string $name, PokemonService $pokemonService): void
    {
        $this->pokemon = $pokemonService->getByName($name);
        $this->name = $this->pokemon['name'];
        $this->height = $this->pokemon['height'];
        $this->weight = $this->pokemon['weight'];
        $this->frontImg = $this->pokemon['frontImg'];
        $this->backImg = $this->pokemon['backImg'];
        $this->artwork = $this->pokemon['artwork'];
        $this->types = $this->pokemon['types'];
    }
    public function render(): View|Factory
    {
        return view('livewire.show-pokemon')->layout('components.layout');
    }
}
