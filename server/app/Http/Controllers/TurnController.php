<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTurnRequest;
use App\Http\Requests\UpdateTurnRequest;
use App\Models\Game;
use App\Models\Square;
use App\Models\Turn;

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
     * Store a newly created resource in storage.
     */
    public function store(StoreTurnRequest $request) {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(int $turnCount): \Illuminate\Http\JsonResponse {
        $latestGame = Game::latest()->first();

        $selectedTurn = $latestGame->turns()
            ->where('turn_count', $turnCount)
            ->first();

        $board = $selectedTurn?->squares->reduce(function ($board, $square) {
            $board[$square->x][$square->y] = $square->disc;
            return $board;
        });

        return response()->json([
            'turnCount' => $turnCount,
            'board' => $board,
            'nextDisc' => $selectedTurn?->next_disc,
            'winnerDisc' => null
        ]);
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
