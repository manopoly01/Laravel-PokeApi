<div>
    <div class="flex flex-col items-center">
        <h1 class="text-xl uppercase mt-8 ">{{ $type }}</h1>
        <a href="/" class="text-blue-500 hover:text-blue-700 mt-3">Back to the types</a>
        <hr class="mt-8 w-1/2 mb-8">
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5">
        @if($pokemonList)
            @foreach ($pokemonList as $pokemon)
                <div class="mt-10 mb-10 flex flex-col justify-between px-5 md:px-10">
                    <a href="/pokemon/{{ $pokemon['name'] }}" class="flex flex-col items-center bg-pokemon-{{ $type }}-bg bg-opacity-50 border-gray-600 rounded-xl py-10 hover:bg-opacity-100 transition">
                        <img height="96px" src="https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/{{ $pokemon['id'] }}.png" alt="{{ $pokemon['name'] }}-image">
                        <h2 class="text-sm semibold uppercase">{{ $pokemon['name'] }}</h2>
                    </a>
                </div>
            @endforeach
        @else

        @endif
    </div>
    <div class="px-10 mb-8">
        {{ $pokemonList->links() }}
    </div>
</div>
