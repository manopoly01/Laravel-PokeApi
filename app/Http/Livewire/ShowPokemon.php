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
    public ?int $pokemon_id = null;
    public ?float $height = null;
    public ?float $weight = null;
    public ?string $frontImg = null;
    public ?string $backImg = null;
    public ?string $artwork = null;
    public ?array $types = null;
    public ?array $noDamageTo = null;
    public ?array $noDamageFrom = null;
    public ?array $halfDamageTo = null;
    public ?array $halfDamageFrom = null;
    public ?array $doubleDamageTo = null;
    public ?array $doubleDamageFrom = null;
    public ?int $hp = null;
    public ?int $attack = null;
    public ?int $defense = null;
    public ?int $specialAttack = null;
    public ?int $specialDefense = null;
    public ?int $speed = null;

    public ?array $abilities = null;
    public ?array $descriptions = null;

    public function mount(string $name, PokemonService $pokemonService): void
    {
        $this->pokemon = $pokemonService->getByName($name, 1);
        $this->name = $this->pokemon['name'];
        $this->pokemon_id = $this->pokemon['id'];
        $this->height = $this->pokemon['height'];
        $this->weight = $this->pokemon['weight'];
        $this->frontImg = $this->pokemon['frontImg'];
        $this->backImg = $this->pokemon['backImg'];
        $this->artwork = $this->pokemon['artwork'];
        $this->types = $this->pokemon['types'];
        $this->noDamageTo = $this->pokemon['noDamageTo'];
        $this->noDamageFrom = $this->pokemon['noDamageFrom'];
        $this->halfDamageTo = $this->pokemon['halfDamageTo'];
        $this->halfDamageFrom = $this->pokemon['halfDamageFrom'];
        $this->doubleDamageTo = $this->pokemon['doubleDamageTo'];
        $this->doubleDamageFrom = $this->pokemon['doubleDamageFrom'];
        $this->hp = $this->pokemon['hp'];
        $this->attack = $this->pokemon['attack'];
        $this->defense = $this->pokemon['defense'];
        $this->specialAttack = $this->pokemon['specialAttack'];
        $this->specialDefense = $this->pokemon['specialDefense'];
        $this->speed = $this->pokemon['speed'];
        $this->abilities = $this->pokemon['abilities'];
        $this->descriptions = $this->pokemon['descriptions'];
    }
    public function render(): View|Factory
    {
        return view('livewire.show-pokemon')->layout('components.layout');
    }
}
