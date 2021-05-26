<?php

namespace App\Http\Controllers\Api\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Schedules\SaveScheduleRequest as SaveRequest;
use App\Http\Requests\Schedules\FindScheduleRequest as FindRequest;
use App\Http\Requests\Schedules\PopulateCompanySchedulesRequest as PopulateRequest;

use App\Http\Resources\ScheduleResource;

use App\Repositories\ScheduleRepository;

class ScheduleController extends Controller
{
    private $schedule;

    public function __construct(ScheduleRepository $schedule)
    {
    	$this->schedule = $schedule;
    }

    public function companySchedules(PopulateRequest $request)
    {
    	$options = $request->options();
    	$schedules = $this->schedule->all($options);
    	$schedules = $this->schedule->paginate();
    	$schedules->data = ScheduleResource::collection($schedules);

    	return response()->json(['schedules' => $schedules]);
    }

    public function store(SaveRequest $request)
    {
    	$input = $request->ruleWithCompany();
    	$schedule = $this->schedule->save($input);

    	return apiResponse($this->schedule, ['schedule' => $schedule]);
    }

    public function update(SaveRequest $request)
    {
    	$schedule = $request->getSchedule();
    	$this->schedule->setModel($schedule);

    	$input = $request->ruleWithCompany();
    	$this->schedule->save($input);

    	return apiResponse($this->schedule, ['schedule' => $schedule]);
    }

    public function delete(FindRequest $request)
    {
    	$schedule = $request->getSchedule();
    	$this->schedule->setModel($schedule);
    	$this->schedule->delete();

    	return apiResponse($this->schedule);
    }
}
