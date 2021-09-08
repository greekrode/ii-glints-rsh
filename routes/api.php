<?php

use App\Http\Controllers\TodoController;
use App\Http\Controllers\Api\ResetPassController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\MultipleUploadController;
use App\Http\Controllers\UserController;

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

Route::group(
    [
        'middleware' => 'api',
        'namespace'  => 'App\Http\Controllers',
        'prefix'     => 'auth',
    ],
    function ($router) {
        Route::post('login', 'AuthController@login');
        Route::post('logout', 'AuthController@logout');
        Route::post('register', 'AuthController@register');
    }

);

Route::group(
    [
        'middleware' => 'api',
        'namespace'  => 'App\Http\Controllers',
    ],
    function ($router) {
        Route::resource('todos', 'TodoController');
        Route::resource('images','FileController');
    }
);
   
Route::group(
    [
        'middleware' => 'admin',
    ],
    function ($router) {      
        Route::resource('getusers',UserController::class);
    }
);
// Route::resource('getusers',UserController::class);

Route::post('forgot',[ResetPassController::class,'forgotPassword']);
Route::post('resetpass',[ResetPassController::class,'reset']);