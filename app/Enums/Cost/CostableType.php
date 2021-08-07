<?php

namespace App\Enums\Cost;

use BenSampo\Enum\Enum;

final class CostableType extends Enum 
{
    const Appointment = 1;
    const Worklist = 2;
    const Workday = 3;
}
