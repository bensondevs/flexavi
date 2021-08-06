<?php

namespace App\Enums\Workday;

use BenSampo\Enum\Enum;

final class WorkdayStatus extends Enum
{
    const Prepared = 1;
    const Processed = 2;
    const Calculated = 3;
}