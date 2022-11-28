<?php

declare(strict_types=1);

namespace App\Enums\Setting\Company;

use BenSampo\Enum\Enum;

/**
 * Company's setting key
 */
final class CompanySettingKey extends Enum
{
    public const AutoSubscribePlanWhileEnds = 1;
    public const InvoicingAddressSameAsVisitingAddress = 2;
}
