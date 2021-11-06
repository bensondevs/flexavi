<?php

namespace App\Http\Controllers\Api\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Workdays\Worklists\{
    SaveWorkdayWorklistRequest as SaveRequest,
    AttachWorklistRequest as AttachRequest,
    AttachManyWorklistsRequest as AttachManyRequest,
    DetachWorklistRequest as DetachRequest,
    DetachManyWorklistsRequest as DetachManyRequest
};
use App\Http\Requests\Worklists\PopulateWorkdayWorklistsRequest as PopulateRequest;

use App\Http\Resources\WorklistResource;

use App\Repositories\{
    WorkdayRepository, WorklistRepository
};

class WorkdayWorklistController extends Controller
{
    private $workday;
    private $worklist;

    public function __construct(WorkdayRepository $workday, WorklistRepository $worklist)
    {
        $this->workday = $workday;
        $this->worklist = $worklist;
    }

    public function workdayWorklists(PopulateRequest $request)
    {
        $options = $request->options();

        $worklists = $this->worklist->all($options, true);
        $worklists = WorklistResource::apiCollection($worklists);

        return response()->json(['worklists' => $worklists]);
    }

    public function storeAttach(SaveRequest $request)
    {
        $input = $request->validated();
        // $worklist = $this->worklist->
    }

    public function attach(AttachRequest $request)
    {
        $workday = $request->getWorkday();
        $this->workday->setModel($workday);

        $worklist = $request->getWorklist();
        $this->workday->attachWorklist($worklist);

        return apiResponse($this->worklist);
    }

    public function attachMany(AttachManyRequest $request)
    {
        $workday = $request->getWorkday();
        $this->workday->setModel($workday);

        $worklistIds = $request->worklist_ids;
        $this->workday->attachManyWorklists($worklistIds);

        return apiResponse($this->workday);
    }

    public function detach(DetachRequest $request)
    {
        $workday = $request->getWorkday();
        $this->workday->setModel($workday);

        $worklist = $request->getWorklist();
        $this->workday->detachWorklist($worklist);

        return apiResponse($this->workday);
    }

    public function detachMany(DetachManyRequest $request)
    {
        $workday = $request->getWorkday();
        $this->workday->setModel($workday);

        $worklistIds = $request->worklist_ids;
        $this->workday->detachManyWorklists($worklistIds);

        return apiResponse($this->workday);
    }

    public function truncate(TruncateRequest $request)
    {
        $workday = $request->getWorkday();

        $this->workday->setModel($workday);
        $this->workday->truncateWorklists();

        return apiResponse($this->workday);
    }
}
