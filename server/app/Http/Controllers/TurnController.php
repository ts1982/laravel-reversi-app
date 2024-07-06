<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTurnRequest;
use App\Http\Requests\UpdateTurnRequest;
use App\Models\Turn;
use App\Services\TurnService;
use Illuminate\Http\JsonResponse;

class TurnController extends Controller {
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
     * ターンを保存
     * @param StoreTurnRequest $request
     * @return void
     */
    public function store(StoreTurnRequest $request): void {
        $turnService = new TurnService();
        $turnService->registerTurn($request);
    }

    /**
     * 最新のゲームのターン数に応じた盤面を表示
     * @param int $turn_count
     * @return JsonResponse
     */
    public function show(int $turn_count): JsonResponse {
        $turnService = new TurnService();
        $output = $turnService->findLatestGameTurnByTurnCount($turn_count);

        return response()->json($output);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Turn $turn) {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTurnRequest $request, Turn $turn) {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Turn $turn) {
        //
    }
}
