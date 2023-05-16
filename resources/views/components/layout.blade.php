<!doctype html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>PokéAPI</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        // Hintergrundfarben
                        'pokemon-normal-bg': '#A8A878',
                        'pokemon-fire-bg': '#F08030',
                        'pokemon-water-bg': '#6890F0',
                        'pokemon-electric-bg': '#F8D030',
                        'pokemon-grass-bg': '#78C850',
                        'pokemon-ice-bg': '#98D8D8',
                        'pokemon-fighting-bg': '#C03028',
                        'pokemon-poison-bg': '#A040A0',
                        'pokemon-ground-bg': '#E0C068',
                        'pokemon-flying-bg': '#A890F0',
                        'pokemon-psychic-bg': '#F85888',
                        'pokemon-bug-bg': '#A8B820',
                        'pokemon-rock-bg': '#B8A038',
                        'pokemon-ghost-bg': '#705898',
                        'pokemon-dragon-bg': '#7038F8',
                        'pokemon-dark-bg': '#705848',
                        'pokemon-steel-bg': '#B8B8D0',
                        'pokemon-fairy-bg': '#EE99AC',

                        // Textfarben
                        'pokemon-normal-text': '#000000',
                        'pokemon-fire-text': '#FFFFFF',
                        'pokemon-water-text': '#000000',
                        'pokemon-electric-text': '#000000',
                        'pokemon-grass-text': '#000000',
                        'pokemon-ice-text': '#000000',
                        'pokemon-fighting-text': '#FFFFFF',
                        'pokemon-poison-text': '#FFFFFF',
                        'pokemon-ground-text': '#000000',
                        'pokemon-flying-text': '#000000',
                        'pokemon-psychic-text': '#FFFFFF',
                        'pokemon-bug-text': '#000000',
                        'pokemon-rock-text': '#000000',
                        'pokemon-ghost-text': '#FFFFFF',
                        'pokemon-dragon-text': '#FFFFFF',
                        'pokemon-dark-text': '#FFFFFF',
                        'pokemon-steel-text': '#000000',
                        'pokemon-fairy-text': '#000000',
                    },
                }
            }
        }
    </script>
    @livewireStyles
</head>
<body class="flex flex-col justify-between min-h-screen">

<div class="flex justify-center my-4">
    <a href="/" class="relative w-64 h-32 mr-14">
        <img src="https://pipedream.com/s.v0/app_mvNhzR/logo/orig" alt="Logo" class="w-full h-full object-cover">
        <span class="absolute bottom-0 right-0 transform translate-x-1/2 translate-y-1/2 bg-black text-white px-2 py-1 text-sm font-bold uppercase rounded tracking-widest">by Manuel</span>
    </a>
</div>

<div class="absolute top-10 right-10">
    <livewire:search-pokemon />
</div>

<div>
    {{ $slot }}
</div>

<footer class="bg-gray-900 py-8">
    <div class="container mx-auto flex items-center justify-center">
        <img src="https://img.freepik.com/free-icon/pokeball_318-437806.jpg?w=360" alt="Pokemon Logo" class="h-10 mr-4">
        <p class="text-white text-sm">© 2023 PokéApi by Manuel. All rights reserved.</p>
    </div>
</footer>
@livewireScripts
</body>
</html>
