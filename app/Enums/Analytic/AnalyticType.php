<?php

namespace App\Enums\Analytic;

use BenSampo\Enum\Enum;

final class AnalyticType extends Enum
{
    /**
     * Show the graphic trends of revenue
     * 
     * @var int
     */
    const RevenueTrends = 1;

    /**
     * Show the graphic trends of cost
     * 
     * @var int
     */
    const CostTrends = 2;

    /**
     * Show the graphic trends of profit
     * 
     * @var int
     */
    const ProfitTrends = 3;
}
