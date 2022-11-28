<?php

declare(strict_types=1);

namespace App\Enums\Setting\Company;

use BenSampo\Enum\Enum;

/**
 * CompanyAutoSubscribePlanWhileEnds
 */
final class CompanyInvoicingAddressSameAsVisitingAddress extends Enum
{
    public const Off = 1;
    public const On = 2;
}
