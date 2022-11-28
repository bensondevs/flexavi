<?php

declare(strict_types=1);

namespace App\Enums\Setting\Customer;

use BenSampo\Enum\Enum;

/**
 * CustomerPerPagePagination
 */
final class CustomerPerPagePagination extends Enum
{
    public const Ten = 1 ;
    public const Twenty = 2 ;
    public const Fifty = 3;
    public const Hundred = 4;
}
