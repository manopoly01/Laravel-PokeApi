<!doctype html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>PokéAPI</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="{{ asset('js/tailwind-config.js') }}"></script>
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
<script src="https://cdn.jsdelivr.net/npm/alpinejs@2.8.2"></script>
</body>
</html>
