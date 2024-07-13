<?php

namespace App\Domain\Game;

readonly class GameEntity {
    public function __construct(private int $id) {
    }

    public function getId(): int {
        return $this->id;
    }
}
