<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class DiscType extends Enum {
    public const EMPTY = 0;
    public const DARK = 1;
    public const LIGHT = 2;
}
