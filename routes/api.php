<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FilmsController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/films/import/model', [FilmsController::class, 'importModel'])->name('app.import.films');
Route::get('/films/all', [FilmsController::class, 'all'])->name('app.all.films');
Route::get('/films/filter/winners', [FilmsController::class, 'YearsWithWinner'])->name('app.filter.year');
Route::get('/films/filter/studios', [FilmsController::class, 'byStudios'])->name('app.filter.studios');
Route::get('/films/filter/year', [FilmsController::class, 'byYear'])->name('app.filter.studios');
Route::get('/films/filter/interval', [FilmsController::class, 'byInterval'])->name('app.filter.interval');
Route::post('/films/import/cache', [FilmsController::class, 'importCache'])->name('app.import.cache');