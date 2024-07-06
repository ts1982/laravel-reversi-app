<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class DiscType extends Enum
{
    const EMPTY = 0;
    const DARK = 1;
    const LIGHT = 2;
}
