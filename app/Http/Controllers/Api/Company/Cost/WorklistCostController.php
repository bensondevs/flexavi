<?php

namespace App\Http\Controllers\Api\Company\Cost;

use App\Http\Controllers\Controller;
use App\Http\Requests\Company\Costs\{UnrecordManyCostsRequest as UnrecordManyRequest};
use App\Http\Requests\Company\Costs\RecordCostRequest as RecordRequest;
use App\Http\Requests\Company\Costs\RecordManyCostsRequest as RecordManyRequest;
use App\Http\Requests\Company\Costs\TruncateCostsRequest as TruncateRequest;
use App\Http\Requests\Company\Costs\UnrecordCostRequest as UnrecordRequest;
use App\Http\Requests\Company\Costs\Worklists\PopulateWorklistCostsRequest as PopulateRequest;
use App\Http\Requests\Company\Costs\Worklists\SaveWorklistCostRequest as SaveRequest;
use App\Http\Resources\Cost\CostResource;
use App\Repositories\Cost\CostRepository;

class WorklistCostController extends Controller
{
    /**
     * Cost Repository class container
     *
     * @var
     */
    private $cost;

    /**
     * Controller constructor method
     *
     * @param CostRepository $cost
     * @return void
     */
    public function __construct(CostRepository $cost)
    {
        $this->cost = $cost;
    }

    /**
     * Populate worklist costs
     *
     * @param PopulateRequest $request
     * @return void
     */
    public function worklistCosts(PopulateRequest $request)
    {
        $options = $request->options();

        $costs = $this->cost->all($options, true);
        $costs = CostResource::apiCollection($costs);

        return response()->json(['costs' => $costs]);
    }

    /**
     * Store and record cost to a worklist
     *
     * @param SaveRequest $request
     * @return void
     */
    public function storeRecord(SaveRequest $request)
    {
        $input = $request->validated();
        $this->cost->save($input);

        $worklist = $request->getWorklist();
        $this->cost->record($worklist);

        return apiResponse($this->cost);
    }

    /**
     * Restore cost to a worklist
     *
     * @param RecordRequest $request
     * @return Illuminate\Support\Facades\Response
     */
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

    /**
     * Record many costs to a worklist
     *
     * @param RecordManyRequest $request
     * @return Illuminate\Support\Facades\Response
     */
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

    /**
     * Unrecord cost a from a worklist
     *
     * @param UnrecordRequest $request
     * @return Illuminate\Support\Facades\Response
     */
    public function unrecord(UnrecordRequest $request)
    {
        $cost = $request->getCost();
        $this->cost->setModel($cost);

        $worklist = $request->getWorklist();
        $this->cost->unrecord($worklist);

        return apiResponse($this->cost);
    }

    /**
     * Unrecord many costs from a worklist
     *
     * @param UnrecordManyRequest $request
     * @return Illuminate\Support\Facades\Response
     */
    public function unrecordMany(UnrecordManyRequest $request)
    {
        $worklist = $request->getWorklist();
        $costIds = $request->costIdsArray();

        $this->cost->unrecordMany($worklist, $costIds);

        return apiResponse($this->cost);
    }

    /**
     * Remove all costs from a worklist
     *
     * @param TruncateRequest $request
     * @return Illuminate\Support\Facades\Response
     */
    public function truncate(TruncateRequest $request)
    {
        $worklist = $request->getWorklist();
        $this->cost->truncate($worklist);

        return apiResponse($this->cost);
    }
}
