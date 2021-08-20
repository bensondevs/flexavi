<?php

namespace App\Http\Controllers\Api\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Costs\SaveCostRequest as SaveRequest;
use App\Http\Requests\Costs\FindCostRequest as FindRequest;
use App\Http\Requests\Costs\DeleteCostRequest as DeleteRequest;
use App\Http\Requests\Costs\RestoreCostRequest as RestoreRequest;
use App\Http\Requests\Costs\PopulateCompanyCostsRequest as PopulateRequest;

use App\Http\Resources\CostResource;

use App\Repositories\CostRepository;

class CostController extends Controller
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
     * Populate whole company costs
     * 
     * @param PopulateRequest $request
     * @return json
     */
    public function companyCosts(PopulateRequest $request)
    {
        $options = $request->options();

        $costs = $this->cost->all($options, true);
        $costs = CostResource::apiCollection($costs);

        return response()->json(['costs' => $costs]);
    }

    /**
     * Store cost
     * 
     * @param SaveRequest $request
     * @return json
     */
    public function store(SaveRequest $request)
    {
        $input = $request->validated();
        $cost = $this->cost->save($input);

        return apiResponse($this->cost, ['cost' => new CostResource($cost)]);
    }

    /**
     * View cost with all its relations
     * 
     * @param FindRequest $request
     * @return json
     */
    public function view(FindRequest $request)
    {
        $cost = $request->getCost();
        $cost->load(['appointments', 'worklists', 'workdays']);
        $cost = new CostResource($cost);

        return response()->json(['cost' => $cost]);
    }

    /**
     * Update cost
     * 
     * @param SaveRequest $request
     * @return json
     */
    public function update(SaveRequest $request)
    {
        $cost = $request->getCost();
        $this->cost->setModel($cost);

        $input = $request->validated();
        $this->cost->save($input);

        return apiResponse($this->cost);
    }

    /**
     * Delete cost, WARNING! Deleting cost will detach the record from all relationship upon deleted cost
     * 
     * @param DeleteRequest $request
     * @return json
     */
    public function delete(DeleteRequest $request)
    {
        $cost = $request->getCost();
        $this->cost->setModel($cost);

        $force = $request->force;
        $this->cost->delete($force);

        return apiResponse($this->cost);
    }

    /**
     * Restore cost
     * 
     * @param RestoreRequest $request
     * @return json
     */
    public function restore(RestoreRequest $request)
    {
        $cost = $request->getCost();

        $this->cost->setModel($cost);
        $this->cost->restore();

        return apiResponse($this->cost);
    }
}
