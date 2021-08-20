<?php

namespace App\Http\Controllers\Api\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Workdays\PopulateCompanyWorkdaysRequest as CompanyPopulateRequest;
use App\Http\Requests\Workdays\ProcessWorkdayRequest as ProcessRequest;
use App\Http\Requests\Workdays\FindWorkdayRequest as FindRequest;
use App\Http\Requests\Workdays\CalculateWorkdayRequest as CalculateRequest;

use App\Http\Resources\WorkdayResource;

use App\Repositories\WorkdayRepository;

class WorkdayController extends Controller
{
    private $workday;

    public function __construct(WorkdayRepository $workday)
    {
        $this->workday = $workday;
    }

    public function companyWorkdays(CompanyPopulateRequest $request)
    {
        $options = $request->options();

        $workdays = $this->workday->all($options);
        $workdays = $this->workday->paginate();
        $workdays = WorkdayResource::apiCollection($workdays);

        return response()->json(['workdays' => $workdays]);
    }

    public function currentWorkday(Request $request)
    {
        $user = $request->user();
        $owner = $user->owner;
        $company = $owner->company;
        $workday = $this->workday->current($company);

        return response()->json(['workday' => new WorkdayResource($workday)]);
    }

    public function view(FindRequest $request)
    {
        $workday = $request->getWorkday();
        $workday->load(['worklists', 'appointments', 'costs']);
        $workday = new WorkdayResource($workday);

        return response()->json(['workday' => $workday]);
    }

    public function process(ProcessRequest $request)
    {
        $workday = $request->getWorkday();

        $this->workday->setModel($workday);
        $this->workday->process();

        return apiResponse($this->workday);
    }

    public function calculate(CalculateRequest $request)
    {
        $workday = $request->getWorkday();

        $this->workday->setModel($workday);
        $this->workday->calculate();

        return apiResponse($this->workday);
    }
}