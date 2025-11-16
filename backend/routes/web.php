<?php

use App\Http\Controllers\PeopleController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FilmsController;
use App\Http\Controllers\StatsController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/stats', StatsController::class)->name('stats');

Route::prefix('api')->group(function () {
    Route::get('/films', [FilmsController::class, 'index'])->name('films.index');
    Route::get('/films/{film}', [FilmsController::class, 'show'])->name('films.show');

    Route::get('/people', [PeopleController::class, 'index'])->name('people.index');
    Route::get('/people/{person}', [PeopleController::class, 'show'])->name('people.show');
});
