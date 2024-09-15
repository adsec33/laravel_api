<?php

use App\Http\Controllers\GeolocationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\UserController;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
//

Route::post('/auth/login', [UserController::class, 'login']);

Route::group(['prefix' => 'users'], function () {
    Route::controller(UserController::class)->group(function () {
        Route::post('/', 'store');
    });
});

Route::group(['prefix' => 'geo_locations'], function () {
    Route::controller(GeolocationController::class)->group(function () {
        Route::get('/', 'index');
        Route::get('/{srch}', 'search_geolocation');

        Route::post('/', 'store');

        Route::put('/{id}', 'update');

        Route::delete('/{id}', 'delete');
    });
});

Route::group(['prefix' => 'searches'], function () {
    Route::controller(SearchController::class)->group(function () {

        Route::post('/', 'store');
        Route::get('/', 'index');
    });
});
