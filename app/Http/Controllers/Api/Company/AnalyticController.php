<?php

namespace App\Http\Controllers\Api\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Analytics\AnalyticRepository;

class AnalyticController extends Controller
{
    /**
     * Analytic repository class container
     * 
     * @var \App\Repositories\AnalyticRepository
     */
    private $analytic;

    /**
     * Controller constructor method
     * 
     * @param \App\Repositories\AnalyticRepository  $analytic
     * @return void
     */
    public function __construct(AnalyticRepository $analytic)
    {
        $this->analytic = $analytic;
    }

    /**
     * Revenue and turnover trends
     * 
     * @return Illuminate\Support\Facades\Response
     */
    public function revenueTrends()
    {
        $revenues = $this->analytic
            ->guessDateRange() // This will guess date range
            ->revenueTrends(); // Populate the revenue trends
        return response()->json(['revenues' => $revenues]);
    }

    /**
     * Cost trends
     * 
     * @return Illuminate\Support\Facades\Response
     */
    public function costTrends()
    {
        $costs = $this->analytic
            ->guessDateRange()
            ->costTrends();
        return response()->json(['costs' => $costs]);
    }

    /**
     * Profit trends
     * 
     * @return Illuminate\Support\Facades\Response
     */
    public function profitTrends()
    {
        //
    }
}
