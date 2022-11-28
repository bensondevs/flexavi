<?php

namespace App\Enums\Worklist;

use BenSampo\Enum\Enum;

final class WorklistStatus extends Enum
{
    const Prepared = 1;
    const Processed = 2;
    const Calculated = 3;
}