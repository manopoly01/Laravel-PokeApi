<?php

declare(strict_types=1);

namespace App\Http\Livewire;

use App\Services\PokemonService;
use Illuminate\View\Factory;
use Illuminate\View\View;
use Livewire\Component;

class AllTypes extends Component
{
    public ?array $typesList = null;

    public function mount(PokemonService $pokemonService): void
    {
        $this->typesList = $pokemonService->getTypes();
    }

    public function render(): View|Factory
    {
        return view('livewire.all-types')->layout('components.layout');
    }
}
