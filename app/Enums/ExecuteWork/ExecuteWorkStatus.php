<?php

namespace App\Enums\ExecuteWork;

use BenSampo\Enum\Enum;

final class ExecuteWorkStatus extends Enum
{
    const InProcess = 1;
    const Finished = 2;
}