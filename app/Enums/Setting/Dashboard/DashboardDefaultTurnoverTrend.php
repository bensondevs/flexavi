<?php

declare(strict_types=1);

namespace App\Enums\Setting\Dashboard;

use BenSampo\Enum\Enum;

/**
 * DashboardDefaultTurnoverTrend
 */
final class DashboardDefaultTurnoverTrend extends Enum
{
    public const Weekly = 1;
    public const Monthly = 2;
    public const Yearly = 3;
}
