<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
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

Route::group(['prefix' => 'profiles'], function () {
    Route::controller(ProfileController::class)->group(function () {
        Route::get('/', 'index');
    });
});

Route::group(['prefix' => 'posts'], function () {
    Route::controller(PostController::class)->group(function () {
        Route::get('/', 'index');

        Route::post('/', 'store');

        Route::delete('/{id}', 'delete');
    });
});
