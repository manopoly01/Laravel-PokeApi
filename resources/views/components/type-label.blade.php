@props([
    'name'
])
<a href="/type/{{ $name }}" class="rounded-full mx-1 mb-1 px-2 py-1 text-xs uppercase bg-pokemon-{{ $name }}-bg text-pokemon-{{ $name }}-text">{{ $name }}</a>
