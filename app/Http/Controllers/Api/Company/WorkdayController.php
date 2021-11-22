<?php

namespace App\Http\Controllers\Api\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Workdays\{
    PopulateCompanyWorkdaysRequest as CompanyPopulateRequest,
    ProcessWorkdayRequest as ProcessRequest,
    FindWorkdayRequest as FindRequest,
    CalculateWorkdayRequest as CalculateRequest
};
use App\Http\Resources\WorkdayResource;
use App\Repositories\WorkdayRepository;

class WorkdayController extends Controller
{
    /**
     * Workday Repository class container
     * 
     * @var \App\Repositories\WorkdayRepository
     */
    private $workday;

    /**
     * Controller constructor method
     * 
     * @param \App\Repositories\WorkdayRepository  $workday
     * @return void
     */
    public function __construct(WorkdayRepository $workday)
    {
        $this->workday = $workday;
    }

    /**
     * Populate with company workdays
     * 
     * @param CompanyPopulateRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function companyWorkdays(CompanyPopulateRequest $request)
    {
        $options = $request->options();

        $workdays = $this->workday->all($options);
        $workdays = $this->workday->paginate();
        $workdays = WorkdayResource::apiCollection($workdays);

        return response()->json(['workdays' => $workdays]);
    }

    /**
     * Get current company workday
     * 
     * @param Illuminate\Http\Request  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function currentWorkday(Request $request)
    {
        $user = $request->user();
        $owner = $user->owner;
        $company = $owner->company;
        $workday = $this->workday->current($company);

        return response()->json(['workday' => new WorkdayResource($workday)]);
    }

    /**
     * View company workday
     * 
     * @param FindRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function view(FindRequest $request)
    {
        $workday = $request->getWorkday();
        $workday->load(['worklists', 'appointments', 'costs', 'employees']);
        $workday = new WorkdayResource($workday);

        return response()->json(['workday' => $workday]);
    }

    /**
     * Process company workday
     * 
     * @param ProcessRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function process(ProcessRequest $request)
    {
        $workday = $request->getWorkday();

        $this->workday->setModel($workday);
        $this->workday->process();

        return apiResponse($this->workday);
    }

    /**
     * Calculate company workday
     * 
     * @param CalculateRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function calculate(CalculateRequest $request)
    {
        $workday = $request->getWorkday();

        $this->workday->setModel($workday);
        $this->workday->calculate();

        return apiResponse($this->workday);
    }
}