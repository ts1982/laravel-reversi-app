<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTurnRequest;
use App\Services\TurnService;
use Illuminate\Http\JsonResponse;

class TurnController extends Controller {
    /**
     * ターンを保存
     * @param StoreTurnRequest $request
     * @return void
     */
    public function register_turn(StoreTurnRequest $request): void {
        $turnService = new TurnService();
        $turnService->registerTurn($request);
    }

    /**
     * 最新のゲームのターン数に応じた盤面を表示
     * @param int $turn_count
     * @return JsonResponse
     */
    public function find_latest_game_turn_by_turn_count(int $turn_count): JsonResponse {
        $turnService = new TurnService();
        $output = $turnService->findLatestGameTurnByTurnCount($turn_count);

        return response()->json($output);
    }
}
