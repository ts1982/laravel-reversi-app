<?php

namespace App\Domain\GameResult;

interface GameResultRepository {
    public function findForGameId(int $game_id): ?GameResultEntity;

    public function save(GameResultEntity $game_result_entity): void;
}
