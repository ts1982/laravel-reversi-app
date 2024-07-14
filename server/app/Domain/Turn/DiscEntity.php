<?php

namespace App\Domain\Turn;

use App\Enums\DiscType;

class DiscEntity {
    public static function isOppositeDisc(int $disc1, int $disc2): bool {
        return (($disc1 === DiscType::DARK && $disc2 === DiscType::LIGHT) ||
            ($disc1 === DiscType::LIGHT && $disc2 === DiscType::DARK));
    }
}
