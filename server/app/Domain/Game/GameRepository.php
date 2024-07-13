<?php

namespace App\Domain\Game;

use App\Models\Game;

class GameRepository {
    public function save(): GameEntity {
        $game = Game::create();

        return new GameEntity($game->id);
    }
}
