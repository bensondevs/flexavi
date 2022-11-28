<?php

declare(strict_types=1);

namespace App\Enums\Setting\Dashboard;

use BenSampo\Enum\Enum;

/**
 * DashboardDefaultResultTrend
 */
final class DashboardDefaultResultTrend extends Enum
{
    public const Weekly = 1;
    public const Monthly = 2;
    public const Yearly = 3;
}
