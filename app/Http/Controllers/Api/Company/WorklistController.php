<?php

namespace App\Http\Controllers\Api\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Worklists\{
    PopulateCompanyWorklistsRequest as CompanyPopulateRequest,
    PopulateWorkdayWorklistsRequest as WorkdayPopulateRequest,
    SaveWorklistRequest as SaveRequest,
    FindWorklistRequest as FindRequest,
    DeleteWorklistRequest as DeleteRequest,
    ProcessWorklistRequest as ProcessRequest,
    CalculateWorklistRequest as CalculateRequest,
    RestoreWorklistRequest as RestoreRequest
};
use App\Http\Resources\WorklistResource;
use App\Repositories\WorklistRepository;

class WorklistController extends Controller
{
    /**
     * Worklist Repository Class Container
     * 
     * @var \App\Repositories\WorklistRepository
     */
    private $worklist;

    /**
     * Controller constructor method
     * 
     * @param WorklistRepository  $worklist
     * @return void
     */
    public function __construct(WorklistRepository $worklist)
    {
        $this->worklist = $worklist;
    }

    /**
     * Populate with company worklists
     * 
     * @param CompanyPopulateRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function companyWorklists(CompanyPopulateRequest $request)
    {
        $options = $request->options();

        $worklists = $this->worklist->all($options, true);
        $worklists = WorklistResource::apiCollection($worklists);

        return response()->json(['worklists' => $worklists]);
    }

    /**
     * Populate workday's worklists
     * 
     * @param WorkdayPopulateRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function workdayWorklists(WorkdayPopulateRequest $request)
    {
        $options = $request->options();

        $worklists = $this->worklist->all($options, true);
        $worklists = WorklistResource::apiCollection($worklists);

        return response()->json(['worklists' => $worklists]);
    }

    /**
     * Populate soft-deleted worklists
     * 
     * @param CompanyPopulateRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function trashedWorklists(CompanyPopulateRequest $request)
    {
        $options = $request->options();

        $worklists = $this->worklist->trasheds($options, true);
        $worklists = WorklistResource::apiCollection($worklists);

        return response()->json(['worklists' => $worklists]);
    }

    /**
     * Store worklist 
     * 
     * @param SaveRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function store(SaveRequest $request)
    {
        $input = $request->worklistData();
        $worklist = $this->worklist->save($input);

        return apiResponse($this->worklist);
    }

    /**
     * Assign car to worklist
     * 
     * @param AssignCarRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function assignCar(AssignCarRequest $request)
    {
        $worklist = $request->getWorklist();
        $this->worklist->setModel($worklist);

        $car = $request->getCar();
        $this->worklist->assignCar($car);

        return apiResponse($this->worklist);
    }

    /**
     * Display route of the worklist
     * 
     * @param RouteRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function route(RouteRequest $request)
    {
        //
    }

    /**
     * Process worklist and set the status to
     * \App\Enums\Worklist\WorklistStatus::Processed
     * 
     * @param ProcessRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function process(ProcessRequest $request)
    {
        $worklist = $request->getWorklist();

        $this->worklist->setModel($worklist);
        $this->worklist->process();

        return apiResponse($this->worklist);
    }

    /**
     * Show financial calculation of worklist
     * 
     * @param CalculateRequest  $request
     * @return Illuminate\Support\Facades\Response 
     */
    public function calculate(CalculateRequest $request)
    {
        $worklist = $request->getWorklist();

        $this->worklist->setModel($worklist);
        $this->worklist->calculate();

        return apiResponse($this->worklist);
    }

    /**
     * View worklist detail
     * 
     * @param FindRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function view(FindRequest $request)
    {
        $worklist = $request->getWorklist();

        $relations = $request->relations();
        $worklist->load($relations);
        $worklist = new WorklistResource($worklist);

        return response()->json(['worklist' => $worklist]);
    }

    /**
     * Update Worklist Data
     * 
     * @param SaveRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function update(SaveRequest $request)
    {
        $worklist = $request->getWorklist();
        $this->worklist->setModel($worklist);

        $input = $request->worklistData();
        $this->worklist->save($input);

        return apiResponse($this->worklist);
    }

    /**
     * Delete worklist
     * 
     * @param DeleteRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function delete(DeleteRequest $request)
    {
        $worklist = $request->getWorklist();
        $this->worklist->setModel($worklist);

        $force = $request->force;
        $this->worklist->delete($force);

        return apiResponse($this->worklist);
    }

    /**
     * Restore worklist
     * 
     * @param RestoreRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function restore(RestoreRequest $request)
    {
        $trashedWorklist = $request->getTrashedWorklist();

        $this->worklist->setModel($trashedWorklist);
        $this->worklist->restore();

        return apiResponse($this->worklist);
    }
}