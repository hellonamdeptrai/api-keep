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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('auth')->group(function () {
    Route::post('register', [\App\Http\Controllers\UserController::class,'register']);
    Route::post('login', [\App\Http\Controllers\UserController::class,'login']);
    Route::post('logout', [\App\Http\Controllers\UserController::class,'logout'])->middleware('auth:sanctum');
});

Route::prefix('sync')->group(function () {
    Route::get('', [\App\Http\Controllers\SyncController::class,'index'])->middleware('auth:sanctum');
    Route::post('user', [\App\Http\Controllers\SyncController::class,'storeUser'])->middleware('auth:sanctum');
    Route::post('label', [\App\Http\Controllers\SyncController::class,'storeLabel'])->middleware('auth:sanctum');
    Route::post('note', [\App\Http\Controllers\SyncController::class,'storeNote'])->middleware('auth:sanctum');
    Route::post('note-has-user', [\App\Http\Controllers\SyncController::class,'storeNoteHasUser'])->middleware('auth:sanctum');
});
