<?php

namespace App\Domain\GameResult;

use App\Models\GameResult;

class GameResultRepository {
    public function findForGameId(int $game_id): ?GameResultEntity {
        $game_result = GameResult::whereGameId($game_id)->first();

        if (!$game_result) {
            return null;
        }

        return new GameResultEntity(
            $game_result->game_id,
            $game_result->winner_disc,
        );
    }

    public function save(GameResultEntity $game_result_entity): void {
        GameResult::create([
            'game_id' => $game_result_entity->getGameId(),
            'winner_disc' => $game_result_entity->getWinnerDisc(),
        ]);
    }
}
