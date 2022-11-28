<?php

namespace App\Http\Controllers\Api\Company\Cost;

use App\Http\Controllers\Controller;
use App\Http\Requests\Company\Costs\{UnrecordManyCostsRequest as UnrecordManyRequest};
use App\Http\Requests\Company\Costs\RecordCostRequest as RecordRequest;
use App\Http\Requests\Company\Costs\RecordManyCostsRequest as RecordManyRequest;
use App\Http\Requests\Company\Costs\TruncateCostsRequest as TruncateRequest;
use App\Http\Requests\Company\Costs\UnrecordCostRequest as UnrecordRequest;
use App\Http\Requests\Company\Costs\Workdays\PopulateWorkdayCostsRequest as PopulateRequest;
use App\Http\Requests\Company\Costs\Workdays\SaveWorkdayCostRequest as SaveRequest;
use App\Http\Resources\Cost\CostResource;
use App\Repositories\Cost\CostRepository;

class WorkdayCostController extends Controller
{
    /**
     * Repository Container
     *
     * @var CostRepository|null
     */
    private $cost;

    /**
     * Create New Controller Instance
     *
     * @param CostRepository $cost
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
     * @return Illuminate\Support\Facades\Response
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
     * @return Illuminate\Support\Facades\Response
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
     * @return Illuminate\Support\Facades\Response
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
     * @return Illuminate\Support\Facades\Response
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
     * @return Illuminate\Support\Facades\Response
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
     * @return Illuminate\Support\Facades\Response
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
     * @return Illuminate\Support\Facades\Response
     */
    public function truncate(TruncateRequest $request)
    {
        $workday = $request->getWorkday();
        $this->cost->truncate($workday);
        return apiResponse($this->cost);
    }
}
