<?php

declare(strict_types=1);

namespace App\Enums\Setting\DashboardSetting;

use BenSampo\Enum\Enum;

/**
 * DashboardInvoiceRevenueDateRange's setting key
 */
final class DashboardInvoiceRevenueDateRange extends Enum
{
    public const Daily = 1;
    public const Weekly = 2;
    public const Monthly= 3;
    public const Yearly= 4;
}
