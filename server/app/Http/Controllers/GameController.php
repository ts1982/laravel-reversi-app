<?php

namespace App\Http\Controllers;

use App\Services\GameService;
use Illuminate\Http\JsonResponse;

class GameController extends Controller {
    /**
     * 新規ゲーム作成
     * @return JsonResponse
     */
    public function startNewGameRouter(): JsonResponse {
        $gameService = new GameService();
        $newGame = $gameService->startNewGame();

        return response()->json([
            'message' => 'Game created successfully',
            'game' => $newGame
        ], 201);
    }
}
