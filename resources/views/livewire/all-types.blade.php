<div class="container mx-auto py-8">
    <div class="flex flex-col items-center">
        <h1 class="text-xl uppercase mt-8 ">Types</h1>
        <hr class="mt-8 w-1/2 mb-8">
    </div>

    <div class="grid grid-cols-3 sm:grid-cols-6 md:grid-cols-8 lg:grid-cols-10">
        @foreach($typesList as $typeArray)
            <a href="/type/{{ $typeArray['name'] }}" class="m-4 uppercase opacity-80 hover:opacity-100 transition aspect-square bg-pokemon-{{ $typeArray['name'] }}-bg text-pokemon-{{ $typeArray['name'] }}-text rounded flex items-center justify-center">
                {{ $typeArray['name'] }}
            </a>
        @endforeach
    </div>
</div>
