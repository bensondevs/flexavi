<?php

namespace App\Enums\Quotation;

use BenSampo\Enum\Enum;
use BenSampo\Enum\Contracts\LocalizedEnum;

final class QuotationPaymentMethod extends Enum implements LocalizedEnum
{
    const Cash = 1;
    const BankTransfer = 2;
}