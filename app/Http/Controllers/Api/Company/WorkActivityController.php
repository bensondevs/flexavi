<?php

namespace App\Http\Controllers\Api\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\WorkActivities\PopulateCompanyWorkActivitiesRequest as PopulateRequest;
use App\Http\Requests\WorkActivities\SaveCompanyWorkActivityRequest as SaveRequest;
use App\Http\Requests\WorkActivities\FindCompanyWorkActivityRequest as FindRequest;

use App\Repositories\WorkActivityRepository;

class WorkActivityController extends Controller
{
    private $activity;

    public function __construct(WorkActivityRepository $activity)
    {
    	$this->activity = $activity;
    }

    public function workActivities(PopulateRequest $request)
    {
    	$options = $request->options();
    	$activities = $this->activity->all($options);
    	$activities = $this->activity->paginate();
    	$activities->data = WorkActivityResource::collection($activities);

    	return response()->json(['activities' => $activities]);
    }

    public function store(SaveRequest $request)
    {
    	$input = $request->onlyInRules();
    	$activity = $this->activity->save($input);

    	return apiResponse($this->activity, ['activity' => $activity]);
    }

    public function update(SaveRequest $request)
    {
    	$input = $request->onlyInRules();
    	$activity = $request->getWorkActivity();

    	$this->activity->setModel($activity);
    	$this->activity->save($input);

    	return apiResponse($this->activity, ['activity' => $activity]);
    }

    public function delete(FindRequest $request)
    {
    	$activity = $request->getWorkActivity();

    	$this->activity->setModel($activity);
    	$this->activity->delete();

    	return apiResponse($this->activity);
    }
}
