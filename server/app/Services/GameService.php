<?php

namespace App\Services;

use App\Domain\Game\GameEntity;
use App\Domain\Game\GameRepository;
use App\Domain\Turn\TurnEntity;
use App\Domain\Turn\TurnRepository;

class GameService {
    public function startNewGame(): GameEntity {
        $turn_repository = new TurnRepository();
        $game_repository = new GameRepository();

        $game = $game_repository->save();

        $turn = TurnEntity::firstTurn($game->getId());

        $turn_repository->save($turn);

        return $game;
    }
}
