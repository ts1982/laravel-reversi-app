<?php

namespace App\Domain\Turn;

interface TurnRepository {
    public function findForTurnCount(int $turn_count): TurnEntity;

    public function save(TurnEntity $turn_entity): void;
}
