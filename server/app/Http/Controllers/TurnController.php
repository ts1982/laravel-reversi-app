<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTurnRequest;
use App\Services\TurnService;
use Illuminate\Http\JsonResponse;

class TurnController extends Controller {
    public function __construct(private readonly TurnService $turnService) {
    }

    /**
     * ターンを保存
     * @param StoreTurnRequest $request
     * @return void
     */
    public function registerTurnRouter(StoreTurnRequest $request): void {
        $this->turnService->registerTurn($request);
    }

    /**
     * 最新のゲームのターン数に応じた盤面を表示
     * @param int $turn_count
     * @return JsonResponse
     */
    public function findLatestGameTurnByTurnCountRouter(int $turn_count): JsonResponse {
        $output = $this->turnService->findLatestGameTurnByTurnCount($turn_count);

        return response()->json($output);
    }
}
