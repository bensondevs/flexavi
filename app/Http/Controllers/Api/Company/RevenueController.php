<?php

namespace App\Http\Controllers\Api\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Repositories\RevenueRepository;

class RevenueController extends Controller
{
    private $revenue;

    public function __construct(RevenueRepository $revenue)
    {
        $this->revenue = $revenue;
    }

    public function appointmentRevenue(AppointmentPopulateRequest $request)
    {
        //
    }

    public function worklistRevenue(WorklistPopulateRequest $request)
    {
        //
    }

    public function workdayRevenue(WorkdayPopulateRequest $request)
    {
        //
    }
}
