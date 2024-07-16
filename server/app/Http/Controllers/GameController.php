<?php

namespace App\Http\Controllers;

use App\Services\GameService;
use Illuminate\Http\JsonResponse;

class GameController extends Controller {
    public function __construct(private readonly GameService $game_service) {
    }

    /**
     * 新規ゲーム作成
     * @return JsonResponse
     */
    public function startNewGameRouter(): JsonResponse {
        $newGame = $this->game_service->startNewGame();

        return response()->json([
            'message' => 'Game created successfully',
            'game' => $newGame
        ], 201);
    }
}
