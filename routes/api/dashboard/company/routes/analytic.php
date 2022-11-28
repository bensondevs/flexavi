<?php

use App\Http\Controllers\Api\Company\Analytic\AnalyticController;
use Illuminate\Support\Facades\Route;

/**
 * Company Analytic Module
 */
Route::group(['prefix' => 'analytics'], function () {
    /**
     * @todo Hidden feature for next release
     * TODO: Hidden feature for next release
     *
     *  Route::get('result_trends', [
     *       AnalyticController::class,
     *      'resultTrends',
     *  ]);
     *  Route::get('revenue_trends', [
     *       AnalyticController::class,
     *       'revenueTrends',
     *   ]);
     *   Route::get('cost_trends', [
     *       AnalyticController::class,
     *       'costTrends',
     *   ]);
     *   Route::get('profit_trends', [
     *       AnalyticController::class,
     *       'profitTrends',
     *   ]);
    *
    * Route::get('warranties_per_roofer', [
    *      AnalyticController::class,
    *      'warrantiesPerRoofer',
    *  ]);
    *  Route::get('customer_shortages', [
    *      AnalyticController::class,
    *      'customerShortages',
    *   ]);
    *
    *
    * Route::get('roofer_profit', [
    *   AnalyticController::class,
    *  'rooferProfit'
    * ]);
    */

    Route::get('summaries', [
        AnalyticController::class,
        'summaries'
    ]);
    Route::get('yesterday_cost_revenue', [
        AnalyticController::class,
        'yesterdayCostRevenue'
    ]);
    Route::get("best_selling_services", [
        AnalyticController::class,
        "bestSellingServices"
    ]);

    /**
     *  @todo Hidden feature for next release
     *  TODO: Hidden feature for next release
     *
     * Route::get("best_selling_services_per_poofer", [
     *    AnalyticController::class,
     *    "bestSellingServicesPerRoofer"
     * ]);
     * Route::get("best_roofers_per_province", [
     *     AnalyticController::class,
     *     "bestRoofersPerProvince"
     * ]);
     */
});
