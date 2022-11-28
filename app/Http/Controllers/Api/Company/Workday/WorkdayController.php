<?php

namespace App\Http\Controllers\Api\Company\Workday;

use App\Http\Controllers\Api\Company\Illuminate;
use App\Http\Controllers\Controller;
use App\Http\Requests\Company\Workdays\{PopulateTrashedWorkdayRequest as TrashedRequest};
use App\Http\Requests\Company\Workdays\CalculateWorkdayRequest as CalculateRequest;
use App\Http\Requests\Company\Workdays\DeleteWorkdayRequest as DeleteRequest;
use App\Http\Requests\Company\Workdays\FindWorkdayRequest as FindRequest;
use App\Http\Requests\Company\Workdays\PopulateCompanyWorkdaysRequest as CompanyPopulateRequest;
use App\Http\Requests\Company\Workdays\ProcessWorkdayRequest as ProcessRequest;
use App\Http\Requests\Company\Workdays\RestoreWorkdayRequest as RestoreRequest;
use App\Http\Resources\Workday\WorkdayResource;
use App\Repositories\Workday\WorkdayRepository;

class WorkdayController extends Controller
{
    /**
     * Workday Repository class container
     *
     * @var WorkdayRepository
     */
    private $workday;

    /**
     * Controller constructor method
     *
     * @param WorkdayRepository $workday
     * @return void
     */
    public function __construct(WorkdayRepository $workday)
    {
        $this->workday = $workday;
    }

    /**
     * Populate with company workdays
     *
     * @param CompanyPopulateRequest $request
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
     * @param CompanyPopulateRequest $request
     * @return Illuminate\Support\Facades\Response
     */
    public function currentWorkday(CompanyPopulateRequest $request)
    {
        $user = $request->user();
        $owner = $user->owner;
        $company = $owner->company;

        $workday = $this->workday->current($company)->load($request->relations());

        return response()->json(['workday' => new WorkdayResource($workday)]);
    }

    /**
     * View company workday
     *
     * @param FindRequest $request
     * @return Illuminate\Support\Facades\Response
     */
    public function view(FindRequest $request)
    {
        $workday = $request->getWorkday();
        $workday = $workday->load($request->relations());
        $workday = new WorkdayResource($workday);

        return response()->json(['workday' => $workday]);
    }

    /**
     * Process company workday
     *
     * @param ProcessRequest $request
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
     * @param CalculateRequest $request
     * @return Illuminate\Support\Facades\Response
     */
    public function calculate(CalculateRequest $request)
    {
        $workday = $request->getWorkday();

        $this->workday->setModel($workday);
        $this->workday->calculate();

        return apiResponse($this->workday);
    }

    /**
     * Delete company workday
     *
     * @param CalculateRequest $request
     * @return Illuminate\Support\Facades\Response
     */
    public function delete(DeleteRequest $request)
    {
        $workday = $request->getWorkday();

        $this->workday->setModel($workday);
        $this->workday->delete();

        return apiResponse($this->workday);
    }

    /**
     * restore company workday
     *
     * @param CalculateRequest $request
     * @return Illuminate\Support\Facades\Response
     */
    public function restore(RestoreRequest $request)
    {
        $workday = $request->getWorkday();

        $this->workday->setModel($workday);
        $this->workday->restore();

        return apiResponse($this->workday);
    }

    /**
     * Populate company workday trasheds
     *
     * @param TrashedRequest $request
     * @return Illuminate\Support\Facades\Response
     */
    public function trasheds(TrashedRequest $request)
    {
        $options = $request->options();
        $workdays = $this->workday->trasheds($options, true);
        $workdays = $this->workday->paginate();
        $workdays = WorkdayResource::apiCollection($workdays);
        return response()->json([
            'workdays' => $workdays
        ]);
    }
}
