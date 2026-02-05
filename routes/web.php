<?php

use App\Http\Controllers\CityController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index']);
Route::get('/city/{id}', [CityController::class, 'show'])->whereNumber('id');
Route::get('/search', [SearchController::class, 'index']);
