<?php

namespace App\Enums\Work;

use BenSampo\Enum\Enum;
use BenSampo\Enum\Contracts\LocalizedEnum;

final class WorkStatus extends Enum implements LocalizedEnum
{
    const Created = 1;
    const InProcess = 2;
    const Finished = 3;
    const Unfinished = 4;

    const Active = self::Created | self::InProcess;
}