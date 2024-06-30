<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateGameRequest;
use App\Models\Game;
use App\Models\Square;
use App\Models\Turn;
use Illuminate\Support\Facades\DB;

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
     * Store a newly created resource in storage.
     */
    public function store(): \Illuminate\Http\JsonResponse {
        $EMPTY = 0;
        $DARK = 1;
        $LIGHT = 2;

        $INITIAL_BOARD = [
            [$EMPTY, $EMPTY, $EMPTY, $EMPTY, $EMPTY, $EMPTY, $EMPTY, $EMPTY],
            [$EMPTY, $EMPTY, $EMPTY, $EMPTY, $EMPTY, $EMPTY, $EMPTY, $EMPTY],
            [$EMPTY, $EMPTY, $EMPTY, $EMPTY, $EMPTY, $EMPTY, $EMPTY, $EMPTY],
            [$EMPTY, $EMPTY, $EMPTY, $DARK, $LIGHT, $EMPTY, $EMPTY, $EMPTY],
            [$EMPTY, $EMPTY, $EMPTY, $LIGHT, $DARK, $EMPTY, $EMPTY, $EMPTY],
            [$EMPTY, $EMPTY, $EMPTY, $EMPTY, $EMPTY, $EMPTY, $EMPTY, $EMPTY],
            [$EMPTY, $EMPTY, $EMPTY, $EMPTY, $EMPTY, $EMPTY, $EMPTY, $EMPTY],
            [$EMPTY, $EMPTY, $EMPTY, $EMPTY, $EMPTY, $EMPTY, $EMPTY, $EMPTY],
        ];

        DB::beginTransaction();
        try {
            $game = Game::create();

            $turn = Turn::create([
                'game_id' => $game->id,
                'turn_count' => 0,
                'next_disc' => $DARK
            ]);

            foreach ($INITIAL_BOARD as $y => $line) {
                foreach ($line as $x => $disc) {
                    Square::create([
                        'turn_id' => $turn->id,
                        'x' => $x,
                        'y' => $y,
                        'disc' => $disc
                    ]);
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }

        return response()->json([
            'message' => 'Game created successfully',
            'game' => $game
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
