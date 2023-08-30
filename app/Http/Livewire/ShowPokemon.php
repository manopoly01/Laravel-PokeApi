<?php

declare(strict_types=1);

namespace App\Http\Livewire;

use App\Services\PokemonService;
use Illuminate\Support\Collection;
use Illuminate\View\Factory;
use Illuminate\View\View;
use Livewire\Component;

class ShowPokemon extends Component
{
    public ?Collection $pokemon = null;
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
    public ?Collection $evolutionChain = null;

    public function mount(string $name, PokemonService $pokemonService): void
    {
        $this->pokemon = $pokemonService->getByName($name, 1);
        $pokemon = $this->pokemon->toArray();
        $this->name = $pokemon['name'];
        $this->pokemon_id = $pokemon['id'];
        $this->height = $pokemon['height'];
        $this->weight = $pokemon['weight'];
        $this->frontImg = $pokemon['images']['frontImg'];
        $this->backImg = $pokemon['images']['backImg'];
        $this->artwork = $pokemon['images']['artwork'];
        $this->types = $pokemon['types'];
        $this->noDamageTo = $pokemon['damageRelations']['noDamageTo'];
        $this->noDamageFrom = $pokemon['damageRelations']['noDamageFrom'];
        $this->halfDamageTo = $pokemon['damageRelations']['halfDamageTo'];
        $this->halfDamageFrom = $pokemon['damageRelations']['halfDamageFrom'];
        $this->doubleDamageTo = $pokemon['damageRelations']['doubleDamageTo'];
        $this->doubleDamageFrom = $pokemon['damageRelations']['doubleDamageFrom'];
        $this->hp = $pokemon['stats']['hp'];
        $this->attack = $pokemon['stats']['attack'];
        $this->defense = $pokemon['stats']['defense'];
        $this->specialAttack = $pokemon['stats']['special-attack'];
        $this->specialDefense = $pokemon['stats']['special-defense'];
        $this->speed = $pokemon['stats']['speed'];
        $this->abilities = $pokemon['abilities'];
        $this->descriptions = $pokemon['descriptions'];

        $this->evolutionChain = $pokemonService->getEvolutionChain($name);
    }
    public function render(): View|Factory
    {
        return view('livewire.show-pokemon')->layout('components.layout');
    }
}
