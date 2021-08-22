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

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::get('test', [\App\Http\Controllers\WX\AuthController::class, 'test']);
Route::get('err', [\App\Http\Controllers\WX\AuthController::class, 'err']);
Route::post('login', [\App\Http\Controllers\WX\AuthController::class, 'login']);
Route::get('user', [\App\Http\Controllers\WX\AuthController::class, 'user']);

Route::resource('project', \App\Http\Controllers\WX\ProjectController::class);
Route::resource('order', \App\Http\Controllers\WX\OrderController::class);
