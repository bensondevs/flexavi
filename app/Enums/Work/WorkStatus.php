<?php

namespace App\Enums\Work;

use BenSampo\Enum\Enum;

final class WorkStatus extends Enum
{
    const Created = 1;
    const InProccess = 2;
    const Finished = 3;
    const Unfinished = 4;
}