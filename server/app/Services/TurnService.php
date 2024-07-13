<?php

namespace App\Services;

use App\Domain\BoardEntity;
use App\Domain\PointEntity;
use App\Domain\TurnEntity;
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

        if (!$selectedTurn) {
            throw new \Error('Specified turn not found');
        }

        $board = $selectedTurn->squares->reduce(function ($board, $square) {
            $board[$square->y][$square->x] = $square->disc;
            return $board;
        });

        return [
            'turnCount' => $turn_count,
            'board' => $board,
            'nextDisc' => $selectedTurn->next_disc,
            'winnerDisc' => null
        ];
    }

    /**
     * ターンの登録
     * @param Request $request
     * @return void
     */
    public function registerTurn(Request $request): void {
        // 一つ前のターンを取得する
        $latest_game = Game::latest()->first();

        $prev_turn_count = $request->input('turnCount') - 1;
        $prev_turn = $latest_game->turns()->where('turn_count', $prev_turn_count)->first();

        if (!$prev_turn) {
            throw new \Error('Specified turn not found');
        }

        $squares = $prev_turn->squares;

        $board = $squares->reduce(function ($board, $square) {
            $board[$square->y][$square->x] = $square->disc;
            return $board;
        });

        $previous_turn = new TurnEntity(
            $latest_game->id,
            $prev_turn_count,
            $prev_turn->next_disc,
            null,
            new BoardEntity($board)
        );

        // 石を置く
        $new_turn = $previous_turn->place_next(
            $request->input('move.disc'),
            new PointEntity($request->input('move.x'), $request->input('move.y'))
        );

        DB::beginTransaction();
        try {
            $turn = Turn::create([
                'game_id' => $new_turn->getGameId(),
                'turn_count' => $new_turn->getTurnCount(),
                'next_disc' => $new_turn->getNextDisc()
            ]);

            foreach ($new_turn->getBoard()->getDiscs() as $y => $line) {
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
                'x' => $new_turn->getMove()?->getPoint()->getX(),
                'y' => $new_turn->getMove()?->getPoint()->getY(),
                'disc' => $new_turn->getMove()?->getDisc()
            ]);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw new RuntimeException($e->getMessage());
        }
    }
}
