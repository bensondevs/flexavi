<?php

declare(strict_types=1);

namespace App\Enums\Setting\EmployeeSetting;

use BenSampo\Enum\Enum;

/**
 * EmployeeInvitationExpiry's setting key
 */
final class EmployeeInvitationExpiry extends Enum
{
    public const Daily = 1;
    public const Weekly = 2;
    public const Monthly= 3;
    public const Yearly= 4;
}
