<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\GameListingController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\TradeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::controller(CityController::class)->prefix('cities')->group(function () {
    Route::get('', 'index')
        ->name('cities.index');
    Route::get('/{id}', 'find')
        ->name('cities.find');
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::controller(GenreController::class)->prefix('genres')->group(function () {
        Route::get('', 'index')
            ->name('genres.index');
        Route::get('/{id}', 'find')
            ->name('genres.find');
    });
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::controller(GameController::class)->prefix('games')->group(function () {
        Route::get('', 'index')
            ->name('games.index');
        Route::get('/{id}', 'find')
            ->name('games.find');
    });
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::controller(GameListingController::class)->prefix('game_listings')->group(function () {
        Route::get('', 'index')
            ->name('game_listings.index');
        Route::get('/{id}', 'find')
            ->name('game_listings.find');
        Route::post('', 'create')
            ->name('game_listings.create');
        Route::put('/{id}', 'update')
            ->name('game_listings.update');
        Route::delete('/{id}', 'simpleDelete')
            ->name('game_listings.delete');
    });
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::controller(TradeController::class)->prefix('trades')->group(function () {
        Route::get('/', 'index')
            ->name('trades.index');
        Route::get('/{id}', 'find')
            ->name('trades.find');
        Route::post('/', 'create')
            ->name('trades.create');
        Route::put('/{id}', 'update')
            ->name('trades.update');
        Route::put('/accept/{id}', 'accept')
            ->name('trades.accept');
        Route::put('/confirm/{id}', 'confirm')
            ->name('trades.confirm');
        Route::put('/cancel/{id}', 'cancel')
            ->name('trades.cancel');
    });
});

Route::controller(AuthController::class)->prefix('auth')->group(function () {
    Route::post('/login', 'login')
        ->name('auth.login');
    Route::post('/logout', 'logout')
        ->name('auth.logout');
    Route::post('/password/reset', 'passwordReset')
        ->name('auth.password.reset');
    Route::post('/register', 'register')
        ->name('auth.register');
    Route::get('/user', 'user')
        ->name('auth.user');
});
