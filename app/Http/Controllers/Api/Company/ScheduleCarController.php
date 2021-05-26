<?php

namespace App\Http\Controllers\Api\Company;

use Illuminate\Http\Request;

use App\Http\Requests\ScheduleCars\SaveScheduleCarRequest as SaveRequest;
use App\Http\Requests\ScheduleCars\FindScheduleCarRequest as FindRequest;
use App\Http\Requests\ScheduleCars\PopulateCompanyScheduleCarsRequest as PopulateRequest;

use App\Repositories\ScheduleCarRepository;

class ScheduleCarController extends Controller
{
    private $scheduleCar;

    public function __construct(
    	ScheduleCarRepository $scheduleCar
    )
    {
    	$this->scheduleCar = $scheduleCar;
    }

    public function companyScheduleCars(PopulateRequest $request)
    {
    	$options = $request->options();

    	$scheduleCars = $this->scheduleCar->all();
    	$scheduleCars = $this->scheduleCars->paginate();
    	$scheduleCars->data = ScheduleCarResource::collection($scheduleCars);

    	return response()->json(['schedule_cars' => $scheduleCars]);
    }

    public function store(SaveRequest $request)
    {
    	$input = $request->onlyInRules();
    	$scheduleCar = $this->scheduleCar->save($input);

    	return apiResponse($this->scheduleCar, ['schedule_car' => $scheduleCar]);
    }

    public function update(SaveRequest $request)
    {
    	$input = $request->onlyInRules();
    	$scheduleCar = $request->getScheduleCar();

    	$this->scheduleCar->setModel($scheduleCar);
    	$this->scheduleCar->save($input);

    	return apiResponse($this->scheduleCar, ['schedule_car' => $scheduleCar]);
    }

    public function delete(FindRequest $request)
    {
    	$scheduleCar = $request->getScheduleCar();

    	$this->scheduleCar->setModel($scheduleCar);
    	$this->scheduleCar->delete();

    	return apiResponse($this->scheduleCar);
    }
}
