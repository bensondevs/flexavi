<?php

namespace App\Enums\Quotation;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class QuotationTypeEnum extends Enum
{
    const Leakage = 1;
    const Renovation = 2;
    const Reparation = 3;
    const Renewal = 4;
}
