<?php

namespace App\Enums\Quotation;

use BenSampo\Enum\Enum;

final class QuotationType extends Enum
{
    const Leakage = 1;
    const Renovation = 2;
    const Reparation = 3;
    const Renewal = 4;
}