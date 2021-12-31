<?php

namespace App\Enums\PaymentTerm;

use BenSampo\Enum\Enum;
use BenSampo\Enum\Contracts\LocalizedEnum;

final class PaymentTermStatus extends Enum implements LocalizedEnum
{
    /**
     * Payment term is unpaid
     * 
     * @var int
     */
    const Unpaid = 1;

    /**
     * Payment term is paid
     * 
     * @var int
     */
    const Paid = 2;

    /**
     * Payment term is forwarded to debt collector
     * 
     * @var int
     */
    const ForwardedToDebtCollector = 3;

    /**
     * Payment term is paid through debt collector
     * 
     * @var int
     */
    const PaidViaDebtCollector = 4;
}
