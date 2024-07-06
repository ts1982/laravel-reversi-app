<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateGameRequest;
use App\Models\Game;
use App\Services\GameService;
use Illuminate\Http\JsonResponse;

class GameController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index() {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {
        //
    }

    /**
     * 新規ゲーム作成
     * @return JsonResponse
     */
    public function store(): JsonResponse {
        $gameService = new GameService();
        $newGame = $gameService->startNewGame();

        return response()->json([
            'message' => 'Game created successfully',
            'game' => $newGame
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Game $game) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Game $game) {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateGameRequest $request, Game $game) {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Game $game) {
        //
    }
}
