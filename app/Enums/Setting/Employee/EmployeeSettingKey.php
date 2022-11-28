<?php

declare(strict_types=1);

namespace App\Enums\Setting\Employee;

use BenSampo\Enum\Enum;

/**
 * Employee's setting key
 */
final class EmployeeSettingKey extends Enum
{
    public const PerPagePagination = 1;
    public const InitialNameWhenNoProfilePhoto = 2;
    public const InvitationExpiry = 3;
}
