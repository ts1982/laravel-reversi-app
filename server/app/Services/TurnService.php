<?php

namespace App\Services;

use App\Enums\DiscType;
use App\Models\Game;
use App\Models\Move;
use App\Models\Square;
use App\Models\Turn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use RuntimeException;

class TurnService {
    /**
     * 最新のゲームからターン数に応じたデータを取得
     * @param int $turn_count
     * @return array
     */
    public function findLatestGameTurnByTurnCount(int $turn_count): array {
        $latestGame = Game::latest()->first();

        $selectedTurn = $latestGame->turns()
            ->where('turn_count', $turn_count)
            ->first();

        $board = $selectedTurn?->squares->reduce(function ($board, $square) {
            $board[$square->y][$square->x] = $square->disc;
            return $board;
        });

        return [
            'turnCount' => $turn_count,
            'board' => $board,
            'nextDisc' => $selectedTurn?->next_disc,
            'winnerDisc' => null
        ];
    }

    /**
     * ターンの登録
     * @param Request $request
     * @return void
     */
    public function registerTurn(Request$request): void {
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
                'next_disc' => $request->input('move.disc') === DiscType::DARK ? DiscType::LIGHT : DiscType::DARK
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
        } catch (Exception $e) {
            DB::rollBack();
            throw new RuntimeException($e->getMessage());
        }
    }
}
