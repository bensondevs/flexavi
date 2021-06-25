<?php

namespace App\Enums\Quotation;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class QuotationPaymentMethodEnum extends Enum
{
    const Cash = 1;
    const BankTransfer = 2;
}