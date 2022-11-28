<?php

namespace App\Enums\Invoice;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class InvoicePaymentMethod extends Enum implements LocalizedEnum
{
    /**
     * Invoice payment method is through cash
     *
     * @var int
     */
    const Cash = 1;

    /**
     * Invoice payment method is through bank transfer
     *
     * @var int
     */
    const BankTransfer = 2;
}
