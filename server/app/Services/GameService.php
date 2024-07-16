<?php

namespace App\Services;

use App\Domain\Game\GameEntity;
use App\Domain\Turn\TurnEntity;
use App\Domain\Game\GameRepository;
use App\Domain\Turn\TurnRepository;

readonly class GameService {
    public function __construct(
        private GameRepository $game_repository,
        private TurnRepository $turn_repository
    ) {
    }

    public function startNewGame(): GameEntity {
        $game = $this->game_repository->save();

        $turn = TurnEntity::firstTurn($game->getId());

        $this->turn_repository->save($turn);

        return $game;
    }
}
