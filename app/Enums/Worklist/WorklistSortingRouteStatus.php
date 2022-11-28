<?php

namespace App\Enums\Worklist;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class WorklistSortingRouteStatus extends Enum
{
    const Inactive =   1;
    const Active =   2;
}
