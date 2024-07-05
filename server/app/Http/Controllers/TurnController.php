<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTurnRequest;
use App\Http\Requests\UpdateTurnRequest;
use App\Models\Game;
use App\Models\Move;
use App\Models\Square;
use App\Models\Turn;
use Illuminate\Support\Facades\DB;

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
        $DARK = 1;
        $LIGHT = 2;

        $latest_game = Game::latest()->first();

        $prev_turn_count = $request->input('turnCount') - 1;
        $prev_turn = $latest_game->turns()->where('turn_count', $prev_turn_count)->first();
        $squares = $prev_turn?->squares;

        $board = $squares->reduce(function ($board, $square) {
            $board[$square->y][$square->x] = $square->disc;
            return $board;
        });

        // TODO: 盤面に置けるかチェック

        $board[$request->input('move.y')][$request->input('move.x')] = $request->input('move.disc');

        // TODO: ひっくり返す

        DB::beginTransaction();
        try {
            $turn = Turn::create([
                'game_id' => $latest_game->id,
                'turn_count' => $request->input('turnCount'),
                'next_disc' => $request->input('move.disc') === $DARK ? $LIGHT : $DARK
            ]);

            foreach ($board as $y => $line) {
                foreach ($line as $x => $square) {
                    Square::create([
                        'turn_id' => $turn->id,
                        'x' => $x,
                        'y' => $y,
                        'disc' => $square
                    ]);
                }
            }

            Move::create([
                'turn_id' => $turn->id,
                'x' => $request->input('move.x'),
                'y' => $request->input('move.y'),
                'disc' => $request->input('move.disc')
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
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
            $board[$square->y][$square->x] = $square->disc;
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
