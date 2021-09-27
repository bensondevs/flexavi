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
    Workdays\SaveWorkdayCostRequest as SaveRequest,
    Workdays\PopulateWorkdayCostsRequest as PopulateRequest
};

use App\Http\Resources\CostResource;

use App\Repositories\CostRepository;

class WorkdayCostController extends Controller
{
    /**
     * Repository Container 
     * 
     * @var \App\Repositories\CostRepository|null
     */
    private $cost;

    /**
     * Create New Controller Instance
     * 
     * @return void
     */
    public function __construct(CostRepository $cost)
    {
        $this->cost = $cost;
    }

    /**
     * Populate workday costs
     * 
     * @param PopulateRequest $request
     * @return json
     */
    public function workdayCosts(PopulateRequest $request)
    {
        $options = $request->options();

        $costs = $this->cost->all($options, true);
        $costs = CostResource::apiCollection($costs);

        return response()->json(['costs' => $costs]);
    }

    /**
     * Store cost and simuleniously attach it to workday
     * 
     * @param SaveRequest $request
     * @return json
     */
    public function storeRecord(SaveRequest $request)
    {
        $input = $request->validated();
        $this->cost->save($input);
        
        $workday = $request->getWorkday();
        $this->cost->record($workday);

        return apiResponse($this->cost);
    }

    /**
     * Record cost to workday
     * 
     * @param RecordRequest $request
     * @return json
     */
    public function record(RecordRequest $request)
    {
        $cost = $request->getCost();
        $this->cost->setModel($cost);

        $workday = $request->getWorkday();
        $this->cost->record($workday);

        return apiResponse($this->cost);
    }

    /**
     * Record many costs to workday
     * 
     * @param RecordManyRequest $request
     * @return json
     */
    public function recordMany(RecordManyRequest $request)
    {
        $workday = $request->getWorkday();
        $costIds = $request->costIdsArray();

        $this->cost->recordMany($workday, $costIds);

        return apiResponse($this->cost);
    }

    /**
     * Unrecord cost from workday
     * 
     * @param UnrecordRequest $request
     * @return json
     */
    public function unrecord(UnrecordRequest $request)
    {
        $cost = $request->getCost();
        $this->cost->setModel($cost);

        $workday = $request->getWorkday();
        $this->cost->unrecord($workday);

        return apiResponse($this->cost);
    }

    /**
     * Unrecord many costs from workday
     * 
     * @param UnrecordManyRequest $request
     * @return json
     */
    public function unrecordMany(UnrecordManyRequest $request)
    {
        $workday = $request->getWorkday();
        $costIds = $request->costIdsArray();

        $this->cost->unrecordMany($workday, $costIds);

        return apiResponse($this->cost);
    }

    /**
     * Truncate workday costs
     * 
     * @param TruncateRequest $request
     * @return json
     */
    public function truncate(TruncateRequest $request)
    {
        $workday = $request->getWorkday();
        $this->cost->truncate($workday);

        return apiResponse($this->cost);
    }
}