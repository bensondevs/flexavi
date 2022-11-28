<?php

declare(strict_types=1);

namespace App\Enums\Setting\Employee;

use BenSampo\Enum\Enum;

/**
 * EmployeePerPagePagination
 */
final class EmployeePerPagePagination extends Enum
{
    public const Ten = 1 ;
    public const Twenty = 2 ;
    public const Fifty = 3;
    public const Hundred = 4;
}
