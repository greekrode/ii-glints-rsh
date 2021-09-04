<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::group([
    'middleware' => 'api',
    'namespace' => 'App\Http\Controllers',
    'prefix' => 'auth'
], function($router) {
    Route::post('register', [AuthController::class,'register']);
    Route::get('profile', [AuthController::class,'profile']);
    Route::post('refresh', [AuthController::class,'refresh']);
    Route::post('login', [AuthController::class,'login']);
    Route::post('logout', [AuthController::class,'logout']);
});



