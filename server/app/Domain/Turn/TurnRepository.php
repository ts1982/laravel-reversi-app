<?php

namespace App\Domain\Turn;

use App\Models\Game;
use App\Models\Move;
use App\Models\Square;
use App\Models\Turn;
use Illuminate\Support\Facades\DB;

class TurnRepository {
    public function findForTurnCount(int $turn_count): TurnEntity {
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

        $move = $selectedTurn->move;

        $move_entity = null;
        if ($move) {
            $move_entity = new MoveEntity(
                $move->disc,
                new PointEntity($move->x, $move->y)
            );
        }

        return new TurnEntity(
            $latestGame->id,
            $turn_count,
            $selectedTurn->next_disc,
            $move_entity,
            new BoardEntity($board)
        );
    }

    public function save(TurnEntity $turn_entity): void {
        DB::beginTransaction();
        try {
            $turn = Turn::create([
                'game_id' => $turn_entity->getGameId(),
                'turn_count' => $turn_entity->getTurnCount(),
                'next_disc' => $turn_entity->getNextDisc()
            ]);

            foreach ($turn_entity->getBoard()->getDiscs() as $y => $line) {
                foreach ($line as $x => $square) {
                    Square::create([
                        'turn_id' => $turn->id,
                        'x' => $x,
                        'y' => $y,
                        'disc' => $square
                    ]);
                }
            }

            if ($turn_entity->getMove()) {
                Move::create([
                    'turn_id' => $turn->id,
                    'x' => $turn_entity->getMove()->getPoint()->getX(),
                    'y' => $turn_entity->getMove()->getPoint()->getY(),
                    'disc' => $turn_entity->getMove()->getDisc()
                ]);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \RuntimeException($e->getMessage());
        }
    }
}
