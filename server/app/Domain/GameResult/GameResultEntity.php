<?php

namespace App\Domain\GameResult;

readonly class GameResultEntity {
    public function __construct(
        private int      $game_id,
        private int|null $winner_disc,
    ) {
    }

    public function getGameId(): int {
        return $this->game_id;
    }

    public function getWinnerDisc(): ?int {
        return $this->winner_disc;
    }
}
