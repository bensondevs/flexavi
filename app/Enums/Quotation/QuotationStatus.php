<?php

namespace App\Enums\Quotation;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class QuotationStatus extends Enum implements LocalizedEnum
{
    public const Drafted = 1;
    public const Sent = 2;
    public const Nullified = 3;
    public const Signed = 4;
}
