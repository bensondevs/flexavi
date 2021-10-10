<?php

namespace App\Http\Controllers\Api\Company\Costs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Costs\{
    RecordCostRequest as RecordRequest,
    RecordManyCostsRequest as RecordManyRequest,
    UnrecordCostRequest as UnrecordRequest,
    UnrecordManyCostsRequest as UnrecordManyRequest,
    TruncateCostsRequest as TruncateRequest,
    Worklists\SaveWorklistCostRequest as SaveRequest,
    Worklists\PopulateWorklistCostsRequest as PopulateRequest
};

use App\Http\Resources\CostResource;

use App\Models\Worklist;

use App\Repositories\CostRepository;

class WorklistCostController extends Controller
{
    private $cost;

    public function __construct(CostRepository $cost)
    {
        $this->cost = $cost;
    }

    public function worklistCosts(PopulateRequest $request)
    {
        $options = $request->options();

        $costs = $this->cost->all($options, true);
        $costs = CostResource::apiCollection($costs);

        return response()->json(['costs' => $costs]);
    }

    public function storeRecord(SaveRequest $request)
    {
        $input = $request->validated();
        $this->cost->save($input);

        $worklist = $request->getWorklist();
        $this->cost->record($worklist);

        return apiResponse($this->cost);
    }

    public function record(RecordRequest $request)
    {
        $cost = $request->getCost();
        $this->cost->setModel($cost);

        $worklist = $request->getWorklist();
        $this->cost->record($worklist);

        if ($request->record_in_workday && $worklist->workday) {
            $workday = $worklist->workday;
            $this->cost->record($workday);
        }

        return apiResponse($this->cost);
    }

    public function recordMany(RecordManyRequest $request)
    {
        $worklist = $request->getWorklist();
        $costIds = $request->costIdsArray();

        $this->cost->recordMany($worklist, $costIds);

        if ($request->record_in_workday && $worklist->workday) {
            $workday = $worklist->workday;
            $this->cost->recordMany($workday, $costIds);
        }

        return apiResponse($this->cost);
    }

    public function unrecord(UnrecordRequest $request)
    {
        $cost = $request->getCost();
        $this->cost->setModel($cost);

        $worklist = $request->getWorklist();
        $this->cost->unrecord($worklist);

        return apiResponse($this->cost);
    }

    public function unrecordMany(UnrecordManyRequest $request)
    {
        $worklist = $request->getWorklist();
        $costIds = $request->costIdsArray();

        $this->cost->unrecordMany($worklist, $costIds);

        return apiResponse($this->cost);
    }

    public function truncate(TruncateRequest $request)
    {
        $worklist = $request->getWorklist();
        $this->cost->truncate($worklist);

        return apiResponse($this->cost);
    }
}
