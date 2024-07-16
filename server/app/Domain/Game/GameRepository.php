<?php

namespace App\Domain\Game;

interface GameRepository {
    public function save(): GameEntity;
}
