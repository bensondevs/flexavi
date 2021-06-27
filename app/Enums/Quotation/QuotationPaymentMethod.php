<?php

namespace App\Enums\Quotation;

use BenSampo\Enum\Enum;

final class QuotationPaymentMethod extends Enum
{
    const Cash = 1;
    const BankTransfer = 2;
}