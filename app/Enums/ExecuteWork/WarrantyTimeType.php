<?php

namespace App\Enums\ExecuteWork;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class WarrantyTimeType extends Enum
{
    const Year = 1;
    const Month = 2;
    const Week = 3;
    const Day = 4;
}
