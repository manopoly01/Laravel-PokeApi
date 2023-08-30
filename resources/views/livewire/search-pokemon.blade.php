<div x-data="{ isOpen: false }" @click.away="isOpen = false">
    <form wire:submit.prevent="searchPokemon" class="relative">
        <div class="relative">
            <input wire:model="name" type="text" placeholder="Search Pokémon ..."
                   class="p-2 border border-gray-300 rounded-md w-64" @click="isOpen = true">

            <div x-show="isOpen" class="absolute z-10 mt-1 w-64 bg-white rounded-md shadow-lg max-h-96 overflow-y-auto">
                <ul>
                    @if($searchedList)
                        @foreach($searchedList as $searchedPokemon)
                            <li>
                                <a href="/pokemon/{{ $searchedPokemon['name'] }}" class="block px-4 py-2 hover:bg-gray-200"><span class="text-blue-500">#{{ $searchedPokemon['id'] }}</span> {{ ucfirst($searchedPokemon['name']) }}</a>
                            </li>
                        @endforeach
                    @endif
                </ul>
            </div>
        </div>
    </form>
</div>
