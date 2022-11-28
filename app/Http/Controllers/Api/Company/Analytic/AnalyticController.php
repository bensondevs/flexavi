<?php

namespace App\Http\Controllers\Api\Company\Analytic;

use App\Http\Controllers\Api\Company\Illuminate;
use App\Http\Controllers\Controller;
use App\Http\Requests\Company\Analytics\PopulateAnalyticsRequest as PopulateRequest;
use App\Repositories\Analytics\AnalyticRepository;

class AnalyticController extends Controller
{
    /**
     * Analytic repository class container
     *
     * @var AnalyticRepository
     */
    private $analytic;

    /**
     * Controller constructor method
     *
     * @param \App\Repositories\AnalyticRepository $analytic
     * @return void
     */
    public function __construct(AnalyticRepository $analytic)
    {
        $this->analytic = $analytic;
    }

    /**
     * Result Trends
     *
     * @param PopulateRequest $request
     * @return Illuminate\Support\Facades\Response
     */
    public function resultTrends(PopulateRequest $request)
    {
        $results = $this->analytic->recalculate($request->input("recalculate"))
            ->guessGroupBy()
            ->guessDateRange()
            ->result();
        return response()->json(compact("results"));
    }

    /**
     * Revenue and turnover trends
     *
     * @param PopulateRequest $request
     * @return Illuminate\Support\Facades\Response
     */
    public function revenueTrends(PopulateRequest $request)
    {
        $revenues = $this->analytic
            ->recalculate($request->input("recalculate"))
            ->guessGroupBy() // this will guess grouping options
            ->guessDateRange() // This will guess date range
            ->revenue();

        return response()->json(['revenues' => $revenues]);
    }

    /**
     * Cost trends
     *
     * @param PopulateRequest $request
     * @return Illuminate\Support\Facades\Response
     */
    public function costTrends(PopulateRequest $request)
    {
        $costs = $this->analytic
            ->recalculate($request->input("recalculate"))
            ->guessGroupBy()
            ->guessDateRange()
            ->cost();

        return response()->json(['costs' => $costs]);
    }

    /**
     * Profit trends
     *
     * @param PopulateRequest $request
     * @return Illuminate\Support\Facades\Response
     */
    public function profitTrends(PopulateRequest $request)
    {
        $profits = $this->analytic
            ->recalculate($request->input("recalculate"))
            ->guessGroupBy()
            ->guessDateRange()
            ->profit();
        return response()->json(['profits' => $profits]);
    }

    /**
     * Warranties Per Roofer
     *
     * @param PopulateRequest $request
     * @return Illuminate\Support\Facades\Response
     */
    public function warrantiesPerRoofer(PopulateRequest $request)
    {
        $warrantiesPerRoofer = $this->analytic
            ->recalculate($request->input("recalculate"))
            ->guessDateRange()
            ->warrantyPerRoofer();
        return response()->json(['warranties_per_roofer' => $warrantiesPerRoofer]);
    }

    /**
     * Customer shortages
     *
     * @return Illuminate\Support\Facades\Response
     */
    public function customerShortages(PopulateRequest $request)
    {
        $customerShortages = $this->analytic
            ->recalculate($request->input("recalculate"))
            ->guessDateRange()
            ->customerShortage();
        return response()->json([
            "customer_shortages" => $customerShortages
        ]);
    }

    /**
     * Roofer profit
     *
     * @param PopulateRequest $request
     * @return Illuminate\Support\Facades\Response
     */
    public function rooferProfit(PopulateRequest $request)
    {
        $rooferProfit = $this->analytic
            ->recalculate($request->input("recalculate"))
            ->guessDateRange()
            ->rooferProfit();
        return response()->json(["roofer_profit" => $rooferProfit]);
    }

    /**
     * Summary
     *
     * @param PopulateRequest $request
     * @return Illuminate\Support\Facades\Response
     */
    public function summaries(PopulateRequest $request)
    {
        $analytic = $this->analytic->recalculate($request->input("recalculate"));

        if ($request->has("timeframe") || ($request->has("start") && $request->has("end"))) :
            $analytic = $analytic->guessDateRange();
        endif;

        $summaries = $analytic->summary();

        return response()->json(["summaries" => $summaries]);
    }

    /**
     * Best Selling Services
     *
     * @param PopulateRequest $request
     * @return Illuminate\Support\Facades\Response
     */
    public function bestSellingServices(PopulateRequest $request)
    {
        $services = $this->analytic
            ->recalculate($request->input("recalculate"))
            ->guessDateRange()
            ->bestSellingService();

        return response()->json(compact("services"));
    }

    /**
     * Yesterday's cost and revenue
     *
     * @param PopulateRequest $request
     * @return Illuminate\Support\Facades\Response
     */
    public function yesterdayCostRevenue(PopulateRequest $request)
    {
        $costRevenue = $this->analytic
            ->recalculate($request->input("recalculate"))
            ->yesterdayCostRevenue();

        return response()->json($costRevenue);
    }

    /**
     * Best selling services by Roofer
     *
     * @return Illuminate\Support\Facades\Response
     */
    public function bestSellingServicesPerRoofer(PopulateRequest $request)
    {
        $services = $this->analytic
            ->recalculate($request->input("recalculate"))
            ->guessDateRange()
            ->bestSellingServicePerRoofer();

        return response()->json(compact("services"));
    }

    /**
     * Best roofers per province
     *
     * @return Illuminate\Support\Facades\Response
     */
    public function bestRoofersPerProvince(PopulateRequest $request)
    {
        $roofers = $this->analytic
            ->recalculate($request->input("recalculate"))
            ->guessDateRange()
            ->bestRooferPerProvince();

        return response()->json(compact("roofers"));
    }
}
