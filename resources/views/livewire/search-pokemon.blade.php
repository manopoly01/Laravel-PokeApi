<form wire:submit.prevent="searchPokemon" class="relative">
    <input wire:model="name" type="text" placeholder="Search Pokémon ..."
           class="p-2 border border-gray-300 rounded-md w-64">

    @if (! empty($searchedPokemon))
        <div class="absolute z-10 mt-1 w-64 bg-white rounded-md shadow-lg">
            <ul>
                <li>
                    <a href="/pokemon/{{ $searchedPokemon['name'] }}" class="block px-4 py-2 hover:bg-gray-200">{{ ucfirst($searchedPokemon['name']) }}</a>
                </li>
            </ul>
        </div>
    @endif
</form>
