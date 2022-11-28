<?php

declare(strict_types=1);

namespace App\Enums\Setting\Dashboard;

use BenSampo\Enum\Enum;

/**
 * Dashboard's setting key
 */
final class DashboardSettingKey extends Enum
{
    public const DefaultResultGraph = 1;
    public const DefaultTurnoverTrend = 2;
    public const DefaultCostTrend = 3;
    public const DefaultResultTrend = 4;
}
