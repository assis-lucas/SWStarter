<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StatsController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/stats', StatsController::class);
