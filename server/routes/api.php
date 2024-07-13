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

Route::post('/games', [GameController::class, 'start_new_game']);
Route::get('/games/latest/turns/{turnCount}', [TurnController::class, 'find_latest_game_turn_by_turn_count']);
Route::post('/games/latest/turns', [TurnController::class, 'register_turn']);
