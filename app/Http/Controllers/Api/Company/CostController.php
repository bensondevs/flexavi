<?php

namespace App\Http\Controllers\Api\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Repositories\CostRepository;

class CostController extends Controller
{
    private $cost;

    public function __construct(CostRepository $cost)
    {
        $this->cost = $cost;
    }

    public function appointmentCosts(AppointmentPopulateRequest $request)
    {
        //
    }

    public function worklistCosts(WorklistPopulateRequest $request)
    {
        //
    }

    public function workdayCosts(WorkdayPopulateRequest $request)
    {
        //
    }

    public function store(SaveRequest $request)
    {
        //
    }

    public function update(SaveRequest $request)
    {
        //
    }

    public function delete(DeleteRequest $request)
    {
        //
    }
}
