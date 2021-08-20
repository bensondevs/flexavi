<?php

namespace App\Http\Controllers\Api\Company\Costs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Costs\RecordCostRequest as RecordRequest;
use App\Http\Requests\Costs\RecordManyCostsRequest as RecordManyRequest;
use App\Http\Requests\Costs\UnrecordCostRequest as UnrecordRequest;
use App\Http\Requests\Costs\UnrecordManyCostsRequest as UnrecordManyRequest;
use App\Http\Requests\Costs\TruncateCostsRequest as TruncateRequest;
use App\Http\Requests\Costs\Worklist\SaveWorklistCostRequest as SaveRequest;
use App\Http\Requests\Costs\Worklist\PopulateWorklistCostsRequest as PopulateRequest;

use App\Http\Resources\CostResource;

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

    public function store(SaveRequest $request)
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

        return apiResponse($this->cost);
    }

    public function recordMany(RecordManyRequest $request)
    {
        $worklist = $request->getWorklist();
        $costIds = $request->costIdsArray();

        $this->cost->recordMany($worklist, $costIds);

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
