<?php

namespace App\Enums\Quotation;

use BenSampo\Enum\Enum;
use BenSampo\Enum\Contracts\LocalizedEnum;

final class QuotationStatus extends Enum implements LocalizedEnum
{
    const Draft = 1;
    const Sent = 2;
    const Revised = 3;
    const Honored = 4;
    const Cancelled = 5;
}