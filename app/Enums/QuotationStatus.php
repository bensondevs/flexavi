<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class QuotationStatus extends Enum
{
    const Draft = 'draft';
    const Send = 'send';
    const Approval = 'approval';
    const Declined = 'declined';
}
