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
     * @param Illuminate\Http\Request  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function revenueTrends(Request $request)
    {
        if ($type = $request->input('type')) {
            switch (true) {
                case $type == 'last_week':
                    // code...
                    break;
                
                case $type == 'last_month':

                    break;

                default:
                    $type = 'last_week';
                    break;
            }
        }

        if ($request->has('start') && $request->has('end')) {
            //
        }

        $revenueTrends = $this->analytic->revenueTrends();
        return response()->json(['chart_data' => $revenueTrends]);
    }

    /**
     * Cost trends
     * 
     * @return Illuminate\Support\Facades\Response
     */
    public function costTrends()
    {
        //
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
