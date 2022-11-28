<?php

namespace App\Enums\Analytic;

use BenSampo\Enum\Enum;

final class AnalyticType extends Enum
{
    /**
     * Show the graphic Graph of revenue
     * 
     * @var int
     */
    const Revenue = 1;

    /**
     * Show the graphic Graph of cost
     * 
     * @var int
     */
    const Cost = 2;

    /**
     * Show the graphic Graph of profit
     * 
     * @var int
     */
    const Profit = 3;

    /**
     * Show the warranties per roofer
     * 
     * @var int
     */
    const WarrantyPerRoofer = 4;

    /**
     * Show the customer shortage
     * 
     * @var int
     */
    const CustomerShortage = 5;

    /**
     * Show the Best Selling Service 
     * 
     * @var int
     */
    const BestSellingService = 6;

    /**
     * Show the graphic best selling service by roofer
     *
     * @var int
     */
    const BestSellingServicePerRoofer = 6;

    /**
     * Show the graphic best selling service per province
     *
     * @var int
     */
    const BestRooferPerProvince = 7;

    /**
     * Show the result graphic
     *
     * @var int
     */
    const Result = 8;

    /**
     * Show the roofer profit graphic
     *
     * @var int
     */
    const RooferProfit = 9;

    /**
     * Show the roofer profit graphic
     *
     * @var int
     */
    const SummaryCostRevenue = 10;

    /**
     * Show the summary calculated
     *
     * @var int
     */
    const Summary = 11;
}
