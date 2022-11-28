<?php

namespace App\Enums\WorkService;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class WorkServiceStatus extends Enum
{
    const Active =   1;
    const Inactive =   0;
}
