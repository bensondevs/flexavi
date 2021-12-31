<?php

namespace App\Enums\PaymentTerm;

use BenSampo\Enum\Enum;

final class PaymentTermPaymentMethod extends Enum
{
    /**
     * Payment term is paid through cash
     * 
     * @var int
     */
    const Cash = 1;

    /**
     * Payment term is paid through bank transfer
     * 
     * @var int
     */
    const BankTransfer = 2;
}
