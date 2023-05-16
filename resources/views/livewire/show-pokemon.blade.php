<div class="mt-10 mb-10 flex flex-col justify-between px-10 max-w-2xl mx-auto">
    <div class="flex flex-col items-center bg-gray-100 border-gray-600 rounded-xl py-5 hover:bg-gray-200 hover:border-gray-700 transition">
        <div class="mb-10 w-full px-5">
            <a href="{{ url()->previous() }}" class="text-blue-500 hover:text-blue-700 mt-3 transition">Go Back</a>
        </div>
        <div class="flex flex-col items-center space-y-4">
            <h1 class="uppercase text-xl">{{ $name }}</h1>
            <div class="md:flex">
                <div class="md:flex-shrink-0">
                    <img class="h-64" src="{{ $artwork }}" alt="{{ $name }} image">
                    <div class="flex justify-around">
                        <img src="{{ $frontImg }}" alt="">
                        <img src="{{ $backImg }}" alt="">
                    </div>
                </div>
                <div class="p-8 flex flex-col space-y-4">
                    <div>
                        <h3 class="uppercase tracking-wide text-indigo-500 font-semibold">Values</h3>
                        <div class="mt-2 text-gray-600">Height: {{ $height }}m</div>
                        <div class="mt-2 text-gray-600">Weight: {{ $weight }}kg</div>
                    </div>
                    <div>
                        <h3 class="uppercase tracking-wide text-indigo-500 font-semibold">Types</h3>
                        <ul class="list-disc pl-4">
                            @foreach($types as $type)
                                <li class="mt-2 text-gray-600">{{ ucfirst($type['type']['name']) }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
