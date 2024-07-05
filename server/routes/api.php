<?php

use App\Http\Controllers\GameController;
use App\Http\Controllers\TurnController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/users', [UserController::class, 'index']);
Route::post('/games', [GameController::class, 'store']);
Route::get('/games/latest/turns/{turnCount}', [TurnController::class, 'show']);
Route::post('/games/latest/turns', [TurnController::class, 'store']);
