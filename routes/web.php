<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WeatherController; // Add this line
use App\Http\Controllers\PlaceController;   // Add this line

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/weather/{city}', [WeatherController::class, 'getWeather']);
Route::get('/places/{city}', [PlaceController::class, 'getPlaces']);


