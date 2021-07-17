<?php

namespace App\Enums\PaymentTerm;

use BenSampo\Enum\Enum;
use BenSampo\Enum\Contracts\LocalizedEnum;

final class PaymentTermStatus extends Enum implements LocalizedEnum
{
    const Unpaid = 1;
    const Paid = 2;
    const ForwardedToDebtCollector = 3;
}
