<div>
    <div class="max-w-2xl mx-auto my-4">
        <a href="{{ url()->previous() }}" class="text-blue-500 hover:text-blue-700 mt-3 transition">Go Back</a>
    </div>
    <div class="max-w-2xl mx-auto mb-8 mt-4 shadow-lg rounded-lg overflow-hidden border border-gray-300">
        <div class="flex justify-between items-center px-4 py-2 bg-red-600 text-white">
            <div class="flex justify-center items-center space-x-2">
                <h1 class="text-xl font-bold">{{ ucfirst($name) }}</h1>
                <div class="flex space-x-1">
                    <img src="https://cdn-icons-png.flaticon.com/512/1946/1946411.png" alt="" class="h-5">
                    <p class="font-bold text-sm">{{ $pokemon_id }}</p>
                </div>
            </div>
            <div class="text-right">
                <p class="text-xs font-semibold">HP: {{ $hp }}</p>
            </div>
        </div>
        <div class="bg-gray-100">
            <img class="h-64 mx-auto" src="{{ $artwork }}" alt="Pokemon Image">
        </div>
        <div class="p-4 bg-gray-200">
            <div class="flex justify-between items-center mb-4">
                <div class="flex items-center">
                    <span class="mr-1 text-sm font-semibold">Types:</span>
                    @foreach($types as $type)
                        <a href="/type/{{ $type['type']['name'] }}" class="rounded-full mx-1 px-2 py-1 text-xs uppercase bg-pokemon-{{ $type['type']['name'] }}-bg text-pokemon-{{ $type['type']['name'] }}-text">{{ $type['type']['name'] }}</a>
                    @endforeach
                </div>
            </div>
            <div>
                <p class="text-gray-700 text-sm font-semibold">Description:</p>
                @foreach($descriptions as $description)
                    <p class="text-gray-600 text-xs">{{ $description }}</p>
                @endforeach
            </div>
            <div class="mt-4">
                <p class="text-gray-700 text-sm font-semibold">Abilities:</p>
                <ul class="text-gray-600 text-xs mt-2 list-disc">
                    @foreach($abilities as $ability)
                        <li class="ml-3">{{ $ability }}</li>
                    @endforeach
                </ul>
            </div>
            <div class="mt-4">
                <p class="text-gray-700 text-sm font-semibold">Stats:</p>
                <div class="flex space-x-4">
                    <ul class="text-gray-600 text-xs mt-2">
                        <li>HP: {{ $hp }} </li>
                        <li>Attack: {{ $attack }} </li>
                        <li>Defense: {{ $defense }} </li>
                    </ul>
                    <ul class="text-gray-600 text-xs mt-2">
                        <li>Special Attack: {{ $specialAttack }} </li>
                        <li>Special Defense: {{ $specialDefense }} </li>
                        <li>Speed: {{ $speed }} </li>
                    </ul>
                </div>
            </div>
            <div class="border border-gray-500 rounded p-3 mt-4">
                <div class="flex flex-col items-between">
                    <div class="flex flex-wrap items-center mb-2">
                        <span class="mr-1 text-xs font-semibold">No Damage To</span>
                        @foreach($noDamageTo as $type)
                            <x-type-button :name="$type['name']" />
                        @endforeach
                    </div>
                    <div class="flex flex-wrap items-center mb-2">
                        <span class="mr-1 text-xs font-semibold">Half Damage To</span>
                        @foreach($halfDamageTo as $type)
                            <x-type-button :name="$type['name']" />
                        @endforeach
                    </div>
                    <div class="flex flex-wrap items-center mb-2">
                        <span class="mr-1 text-xs font-semibold">Double Damage To</span>
                        @foreach($doubleDamageTo as $type)
                            <x-type-button :name="$type['name']" />
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="border border-gray-500 rounded p-3 mt-4">
                <div class="flex flex-col items-between">
                    <div class="flex flex-wrap items-center mb-2">
                        <span class="mr-1 text-xs font-semibold">No Damage From</span>
                        @foreach($noDamageFrom as $type)
                            <x-type-button :name="$type['name']" />
                        @endforeach
                    </div>
                    <div class="flex flex-wrap items-center mb-2">
                        <span class="mr-1 text-xs font-semibold">Half Damage From</span>
                        @foreach($halfDamageFrom as $type)
                            <x-type-button :name="$type['name']" />
                        @endforeach
                    </div>
                    <div class="flex flex-wrap items-center mb-2">
                        <span class="mr-1 text-xs font-semibold">Double Damage From</span>
                        @foreach($doubleDamageFrom as $type)
                            <x-type-button :name="$type['name']" />
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
