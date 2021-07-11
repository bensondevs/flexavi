<?php

namespace App\Enums\Employee;

use BenSampo\Enum\Enum;

final class EmploymentStatus extends Enum
{
    const Active = 1;
    const Inactive = 2;
    const Fired = 3;
}