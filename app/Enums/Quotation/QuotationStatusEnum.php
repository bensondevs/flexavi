<?php

namespace App\Enums\Quotation;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class QuotationStatusEnum extends Enum
{
    const Draft = 1;
    const Send = 2;
    const Revised = 3;
    const Honored = 4;
    const Cancelled = 5;
}
