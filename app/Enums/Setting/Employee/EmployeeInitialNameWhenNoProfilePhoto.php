<?php

declare(strict_types=1);

namespace App\Enums\Setting\Employee;

use BenSampo\Enum\Enum;

/**
 * EmployeeInitialNameWhenNoProfilePhoto
 */
final class EmployeeInitialNameWhenNoProfilePhoto extends Enum
{
    public const Off = 1;
    public const On = 2;
}
