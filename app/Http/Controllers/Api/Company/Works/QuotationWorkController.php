<?php

namespace App\Http\Controllers\Api\Company\Works;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Works\PopulateQuotationWorksRequest as PopulateRequest;

use App\Http\Resources\WorkResource;

use App\Repositories\WorkRepository;

class QuotationWorkController extends Controller
{
    /**
     * Repository Container 
     * 
     * @var \App\Repositories\WorkRepository
     */
    private $work;

    /**
     * Create New Controller Instance
     * 
     * @return void
     */
    public function __construct(WorkRepository $work)
    {
        $this->work = $work;
    }

    /**
     * Populate works that attached within quotation
     * 
     * @param PopulateRequest $request
     * @return json
     */
    public function quotationWorks(PopulateRequest $request)
    {
        $options = $request->options();

        $works = $this->work->all($options, true);
        $works = WorkRepository::apiCollection($works);

        return response()->json(['works' => $works]);
    }

    /**
     * Store work and directly attach it to quotation
     * 
     * @param SaveRequest $request
     * @return json
     */
    public function storeAttach(SaveRequest $request)
    {
        $input = $request->validated();
        $this->work->save($input);

        $quotation = $request->getQuotation();
        $this->work->attachTo($quotation);

        return apiResponse($this->work);
    }

    /**
     * Attach work to quotation
     * 
     * @param AttachRequest $request
     * @return json
     */
    public function attach(AttachRequest $request)
    {
        $work = $request->getWork();
        $this->work->setModel($work);

        $quotation = $request->getQuotation();
        $this->work->attachTo($quotation);

        return apiResponse($this->work);
    }

    /**
     * Attach many works to quotation
     * 
     * @param AttachManyRequest $request
     * @return json
     */
    public function attachMany(AttachManyRequest $request)
    {
        $quotation = $request->getQuotation();
        $workIds = $request->input('work_ids');

        $this->work->attachToMany($quotation, $workIds);
        return apiResponse($this->work);
    }

    /**
     * Detach work from quotation
     * 
     * @param DetachRequest $request
     * @return json
     */
    public function detach(DetachRequest $request)
    {
        $work = $request->getWork();
        $this->work->setModel($work);

        $quotation = $request->getQuotation();
        $this->work->detachFrom($quotation);

        return apiResponse($this->work);
    }

    /**
     * Detach many works from quotation
     * 
     * @param DetachManyRequest $request
     * @return json
     */
    public function detachMany(DetachManyRequest $request)
    {
        $quotation = $request->getQuotation();
        $workIds = $request->work_ids;

        $this->work->detachFromMany($quotation, $workIds);
        return apiResponse($this->work);
    }

    /**
     * Truncate works inside quotation
     * 
     * @param TruncateRequest $request
     * @return json
     */
    public function truncate(TruncateRequest $request)
    {
        $quotation = $request->getQuotation();
        $this->work->truncate($quotation);
        return apiResponse($this->work);
    }
}