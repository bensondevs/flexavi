<?php

namespace App\Http\Controllers\Api\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Repositories\RevenueRepository;

class RevenueController extends Controller
{
    /**
     * Repository Container 
     * 
     * @var \App\Repositories\RevenueRepository|null
     */
    private $revenue;

    /**
     * Create New Controller Instance
     * 
     * @return void
     */
    public function __construct(RevenueRepository $revenue)
    {
        $this->revenue = $revenue;
    }

    /**
     * Populate whole company revenues
     * 
     * @param PopulateRequest $request
     * @return json
     */
    public function companyRevenues(PopulateRequest $request)
    {
        $options = $request->options();

        $revenues = $this->revenue->all($options, true);
        $revenues = RevenueResource::apiCollection($revenues);

        return response()->json(['revenues' => $revenues]);
    }

    /**
     * Store revenue
     * 
     * @param SaveRequest $request
     * @return json
     */
    public function store(SaveRequest $request)
    {
        $input = $request->validated();
        $this->revenue->save($input);

        return apiResponse($this->revenue);
    }

    /**
     * Update revenue
     * 
     * @param SaveRequest $request
     * @return json
     */
    public function update(SaveRequest $request)
    {
        $revenue = $request->getRevenue();
        $this->revenue->setModel($revenue);

        $input = $request->validated();
        $this->revenue->save($input);

        return apiResponse($this->revenue);
    }

    /**
     * Delete revenue, WARNING! deleting revenue will detach the record from all relationship upon deleted revenue
     * 
     * @param DeleteRequest $request
     * @return json
     */
    public function delete(DeleteRequest $request)
    {
        $revenue = $request->getRevenue();

        $this->revenue->setModel($revenue);
        $this->revenue->delete($request->force);

        return apiResponse($this->revenue);
    }

    /**
     * Restore revenue
     * 
     * @param RestoreRequest $request
     * @return json
     */
    public function restore(RestoreRequest $request)
    {
        $revenue = $request->getDeletedRevenue();

        $this->revenue->setModel($revenue);
        $this->revenue->restore();

        return apiResponse($this->revenue);
    }
}
