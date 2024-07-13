<?php

namespace App\Domain;

readonly class PointEntity {
    public function __construct(private int $x, private int $y) {
    }

    public function getX(): int {
        return $this->x;
    }

    public function getY(): int {
        return $this->y;
    }
}
