<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class QuotationType extends Enum
{
    const Leakage = 'leakage';
    const Renovation = 'renovation';
    const Reparation = 'reparation';
    const Renewal = 'renewal';
}
