<?php

use App\Http\Controllers\FilmsController;
use App\Http\Controllers\PeopleController;
use App\Http\Controllers\StatsController;
use App\Http\Middleware\CaptureRequestInformation;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\HomeController;

Route::get('/', [HomeController::class, 'index']);
Route::get('/processStats', [StatsController::class, 'processStats']);
Route::get('/aggregated-stats', [StatsController::class, 'getAggregatedStats']);
Route::middleware([CaptureRequestInformation::class])->group(function () {
    Route::get('/people', [PeopleController::class, 'getPeople'])->name('people');
    Route::get('/people/{id}', [PeopleController::class, 'getPeopleById'])->name('people');
    Route::get('/films', [FilmsController::class, 'getFilms'])->name('films');
    Route::get('/films/{id}', [FilmsController::class, 'getFilmsBtId'])->name('films');
});
