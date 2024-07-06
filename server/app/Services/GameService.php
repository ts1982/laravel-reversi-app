<?php

namespace App\Services;

use App\Enums\DiscType;
use App\Models\Game;
use App\Models\Square;
use App\Models\Turn;
use Illuminate\Support\Facades\DB;
use Exception;
use RuntimeException;

class GameService {
    public function startNewGame(): Game {
        $INITIAL_BOARD = [
            [DiscType::EMPTY, DiscType::EMPTY, DiscType::EMPTY, DiscType::EMPTY, DiscType::EMPTY, DiscType::EMPTY, DiscType::EMPTY, DiscType::EMPTY],
            [DiscType::EMPTY, DiscType::EMPTY, DiscType::EMPTY, DiscType::EMPTY, DiscType::EMPTY, DiscType::EMPTY, DiscType::EMPTY, DiscType::EMPTY],
            [DiscType::EMPTY, DiscType::EMPTY, DiscType::EMPTY, DiscType::EMPTY, DiscType::EMPTY, DiscType::EMPTY, DiscType::EMPTY, DiscType::EMPTY],
            [DiscType::EMPTY, DiscType::EMPTY, DiscType::EMPTY, DiscType::DARK, DiscType::LIGHT, DiscType::EMPTY, DiscType::EMPTY, DiscType::EMPTY],
            [DiscType::EMPTY, DiscType::EMPTY, DiscType::EMPTY, DiscType::LIGHT, DiscType::DARK, DiscType::EMPTY, DiscType::EMPTY, DiscType::EMPTY],
            [DiscType::EMPTY, DiscType::EMPTY, DiscType::EMPTY, DiscType::EMPTY, DiscType::EMPTY, DiscType::EMPTY, DiscType::EMPTY, DiscType::EMPTY],
            [DiscType::EMPTY, DiscType::EMPTY, DiscType::EMPTY, DiscType::EMPTY, DiscType::EMPTY, DiscType::EMPTY, DiscType::EMPTY, DiscType::EMPTY],
            [DiscType::EMPTY, DiscType::EMPTY, DiscType::EMPTY, DiscType::EMPTY, DiscType::EMPTY, DiscType::EMPTY, DiscType::EMPTY, DiscType::EMPTY],
        ];

        DB::beginTransaction();
        try {
            $game = Game::create();

            $turn = Turn::create([
                'game_id' => $game->id,
                'turn_count' => 0,
                'next_disc' => DiscType::DARK
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
        } catch (Exception $e) {
            DB::rollBack();
            throw new RuntimeException($e->getMessage());
        }

        return $game;
    }
}
