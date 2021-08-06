<?php

namespace App\Http\Controllers\Api\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\AppointmentCosts\PopulateAppointmentCostsRequest as PopulateRequest;
use App\Http\Requests\AppointmentCosts\SaveAppointmentCostRequest as SaveRequest;
use App\Http\Requests\AppointmentCosts\DeleteAppointmentCostRequest as DeleteRequest;

use App\Http\Resources\AppointmentCostResource;

use App\Repositories\AppointmentCostRepository;

class AppointmentCostController extends Controller
{
    private $cost;

    public function __construct(AppointmentCostRepository $cost)
    {
        $this->cost = $cost;
    }

    public function appointmentCosts(PopulateRequest $request)
    {
        $options = $request->options();

        $costs = $this->cost->all($options);
        $costs = $this->cost->paginate();
        $costs = AppointmentCostResource::apiCollection($costs);

        return response()->json(['appointment_costs' => $costs]);
    }

    public function store(SaveRequest $request)
    {
        $input = $request->validated();
        $this->cost->save($input);

        return apiResponse($this->cost);
    }

    public function update(SaveRequest $request)
    {
        $cost = $request->getAppointmentCost();
        $this->cost->setModel($cost);

        $input = $request->validated();
        $this->cost->save($input);

        return apiResponse($this->cost);
    }

    public function delete(DeleteRequest $request)
    {
        $cost = $request->getCost();

        $this->cost->setModel($cost);
        $this->cost->delete();

        return apiResponse($this->cost);
    }
}
