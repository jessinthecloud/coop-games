<?php

use App\Http\Controllers\GameController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PlatformController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [PageController::class, 'index'])->name('home');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');

Route::get('/games/{page?}', [GameController::class, 'index'])
    ->name('games.index')
    ->where('page', '[0-9]+')
    ;
Route::get('/games/{slug}', [GameController::class, 'show'])->name('games.show');

Route::get('/platforms', [PlatformController::class, 'index'])->name('platforms.index');
Route::get('/platforms/{slug}', [PlatformController::class, 'show'])->name('platforms.show');

Route::get('/genres', [GenreController::class, 'index'])->name('genres.index');
Route::get('/genres/{slug}', [GenreController::class, 'show'])->name('genres.show');
