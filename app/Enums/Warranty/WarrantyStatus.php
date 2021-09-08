<?php

namespace App\Enums\Warranty;

use BenSampo\Enum\Enum;

final class WarrantyStatus extends Enum
{
    const Created = 1;
    const InProcess = 2;
    const Fixed = 3;
    const Unfixed = 4;
}
