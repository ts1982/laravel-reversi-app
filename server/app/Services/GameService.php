<?php

namespace App\Services;

use App\Domain\Game\GameEntity;
use App\Domain\Game\GameRepository;
use App\Domain\Turn\TurnEntity;
use App\Domain\Turn\TurnRepository;

class GameService {
    public function startNewGame(): GameEntity {
        $turnRepository = new TurnRepository();
        $gameRepository = new GameRepository();

        $game = $gameRepository->save();

        $turn = TurnEntity::firstTurn($game->getId());

        $turnRepository->save($turn);

        return $game;
    }
}
