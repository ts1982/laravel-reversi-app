<?php

namespace App\Infrastructure\Game;

use App\Domain\Game\GameEntity;
use App\Domain\Game\GameRepository;
use App\Models\Game;

class GameMySQLRepository implements GameRepository {
    public function save(): GameEntity {
        $game = Game::create();

        return new GameEntity($game->id);
    }
}
