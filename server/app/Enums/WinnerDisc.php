<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class WinnerDisc extends Enum {
    public const Draw = 0;
    public const Dark = 1;
    public const Light = 2;
}
