<?php

namespace App\Enums\Invoice;

use BenSampo\Enum\Enum;
use BenSampo\Enum\Contracts\LocalizedEnum;

final class InvoicePaymentMethod extends Enum implements LocalizedEnum
{
    const Cash = 1;
    const BankTransfer = 2;
}