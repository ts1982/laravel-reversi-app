<?php

namespace App\Domain\Turn;

readonly class MoveEntity {
    public function __construct(private int $disc, private PointEntity $point) {
    }

    public function getPoint(): PointEntity {
        return $this->point;
    }

    public function getDisc(): int {
        return $this->disc;
    }
}
