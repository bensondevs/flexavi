<?php

namespace App\Http\Controllers\Api\Company\Revenue;

use App\Http\Controllers\Api\Company\Illuminate;
use App\Http\Controllers\Controller;
use App\Http\Requests\Company\Revenues\{DeleteRevenueRequest as DeleteRequest};
use App\Http\Requests\Company\Revenues\FindRevenueRequest as FindRequest;
use App\Http\Requests\Company\Revenues\PopulateCompanyRevenuesRequest as PopulateRequest;
use App\Http\Requests\Company\Revenues\RestoreRevenueRequest as RestoreRequest;
use App\Http\Requests\Company\Revenues\SaveRevenueRequest as SaveRequest;
use App\Http\Resources\Revenue\RevenueResource;
use App\Repositories\Revenue\RevenueRepository;

class RevenueController extends Controller
{
    /**
     * Repository Container
     *
     * @var RevenueRepository|null
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
     * @return Illuminate\Support\Facades\Response
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
     * @return Illuminate\Support\Facades\Response
     */
    public function store(SaveRequest $request)
    {
        $input = $request->validated();
        $revenue = $this->revenue->save($input);
        return apiResponse($this->revenue, ['revenue' => $revenue]);
    }

    /**
     * View revenue
     *
     * @param FindRequest
     * @return Illuminate\Support\Facades\Response
     */
    public function view(FindRequest $request)
    {
        $revenue = $request->getRevenue();
        $revenue->load(['revenueables']);

        return response()->json(['revenue' => $revenue]);
    }

    /**
     * Update revenue
     *
     * @param SaveRequest $request
     * @return Illuminate\Support\Facades\Response
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
     * Delete revenue, WARNING! deleting revenue will detach the record
     * from all relationship upon deleted revenue
     *
     * @param DeleteRequest $request
     * @return Illuminate\Support\Facades\Response
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
     * @return Illuminate\Support\Facades\Response
     */
    public function restore(RestoreRequest $request)
    {
        $revenue = $request->getDeletedRevenue();

        $this->revenue->setModel($revenue);
        $this->revenue->restore();

        return apiResponse($this->revenue);
    }
}
