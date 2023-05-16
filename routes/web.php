<?php

declare(strict_types=1);

use App\Http\Livewire\AllPokemons;
use App\Http\Livewire\AllTypes;
use App\Http\Livewire\ShowPokemon;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', AllTypes::class);
Route::get('/type/{name}', AllPokemons::class);
Route::get('/pokemon/{name}', ShowPokemon::class);
