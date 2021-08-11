<?php

namespace App\Http\Controllers\Api\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Costs\PopulateWorklistCostsRequest as WorklistPopulateRequest;
use App\Http\Requests\Costs\PopulateWorkdayCostsRequest as WorkdayPopulateRequest;

use App\Http\Resources\CostResource;

use App\Repositories\CostRepository;

class CostController extends Controller
{
    private $cost;

    public function __construct(CostRepository $cost)
    {
        $this->cost = $cost;
    }

    public function worklistCosts(WorklistPopulateRequest $request)
    {
        $options = $request->options();

        $costs = $this->cost->all($options, true);
        $costs = CostResource::apiCollection($costs);

        return response()->json(['costs' => $costs]);
    }

    public function workdayCosts(WorkdayPopulateRequest $request)
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

        return apiResponse($this->cost);
    }

    public function update(SaveRequest $request)
    {
        $cost = $request->getCost();
        $this->cost->setModel($cost);

        $input = $request->validated();
        $this->cost->save($input);

        return apiResponse($this->cost);
    }

    public function delete(DeleteRequest $request)
    {
        $cost = $request->getCost();
        $this->cost->setModel($cost);

        $force = $request->force;
        $this->cost->delete($force);

        return apiResponse($this->cost);
    }
}
