<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\TagController;
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
Route::middleware(['auth:sanctum'])->group(function () {
    Route::controller(CityController::class)->prefix('cities')->group(function () {
        Route::get('', 'index')
            ->name('cities.index');
        Route::get('/{id}', 'find')
            ->name('cities.find');
    });
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
    Route::controller(TagController::class)->prefix('tags')->group(function () {
        Route::get('', 'index')
            ->name('tags.index');
        Route::get('/{id}', 'find')
            ->name('tags.find');
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
