<?php

namespace App\Http\Controllers\Api\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Worklists\PopulateCompanyWorklistsRequest as CompanyPopulateRequest;
use App\Http\Requests\Worklists\PopulateWorkdayWorklistsRequest as WorkdayPopulateRequest;
use App\Http\Requests\Worklists\SaveWorklistRequest as SaveRequest;
use App\Http\Requests\Worklists\FindWorklistRequest as FindRequest;
use App\Http\Requests\Worklists\DeleteWorklistRequest as DeleteRequest;

use App\Http\Resources\WorklistResource;

use App\Repositories\WorklistRepository;

class WorklistController extends Controller
{
    private $worklist;

    public function __construct(WorklistRepository $worklist)
    {
        $this->worklist = $worklist;
    }

    public function companyWorklists(CompanyPopulateRequest $request)
    {
        $options = $request->options();

        $worklists = $this->worklist->all($options, true);
        $worklists = WorklistResource::apiCollection($worklists);

        return response()->json(['worklists' => $worklists]);
    }

    public function workdayWorklists(WorkdayPopulateRequest $request)
    {
        $options = $request->options();

        $worklists = $this->worklist->all($options, true);
        $worklists = WorklistResource::apiCollection($worklists);

        return response()->json(['worklists' => $worklists]);
    }

    public function trashedWorklists(CompanyPopulateRequest $request)
    {
        $options = $request->options();

        $worklists = $this->worklist->trasheds($options, true);
        $worklists = WorklistResource::apiCollection($worklists);

        return response()->json(['worklists' => $worklists]);
    }

    public function store(SaveRequest $request)
    {
        $input = $request->worklistData();
        $worklist = $this->worklist->save($input);

        return apiResponse($this->worklist, ['worklist' => $worklist]);
    }

    public function process(ProcessRequest $request)
    {
        $worklist = $request->getWorklist();

        $this->worklist->setModel($worklist);
        $this->worklist->process();

        return apiResponse($this->worklist);
    }

    public function calculate(CalculateRequest $request)
    {
        $worklist = $request->getWorklist();

        $this->worklist->setModel($worklist);
        $this->worklist->calculate();

        return apiResponse($this->worklist);
    }

    public function view(FindRequest $request)
    {
        $worklist = $request->getWorklist();
        $worklist->load(['workday', 'appointments', 'costs']);
        $worklist = new WorklistResource($worklist);

        return response()->json(['worklist' => $worklist]);
    }

    public function update(SaveRequest $request)
    {
        $worklist = $request->getWorklist();
        $this->worklist->setModel($worklist);

        $input = $request->worklistData();
        $this->worklist->save($input);

        return apiResponse($this->worklist);
    }

    public function delete(DeleteRequest $request)
    {
        $worklist = $request->getWorklist();
        $this->worklist->setModel($worklist);

        $force = $request->force;
        $this->worklist->delete($force);

        return apiResponse($this->worklist);
    }

    public function restore()
    {
        $trashedWorklist = $request->getTrashedWorklist();

        $this->worklist->setModel($trashedWorklist);
        $this->worklist->restore();

        return apiResponse($this->worklist);
    }
}
