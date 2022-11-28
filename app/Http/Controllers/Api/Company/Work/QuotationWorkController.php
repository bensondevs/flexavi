<?php

namespace App\Http\Controllers\Api\Company\Work;

use App\Http\Controllers\Controller;
use App\Http\Requests\Company\Works\{AttachManyWorksRequest as AttachManyRequest};
use App\Http\Requests\Company\Works\AttachWorkRequest as AttachRequest;
use App\Http\Requests\Company\Works\DetachManyWorksRequest as DetachManyRequest;
use App\Http\Requests\Company\Works\DetachWorkRequest as DetachRequest;
use App\Http\Requests\Company\Works\Quotations\PopulateQuotationWorksRequest as PopulateRequest;
use App\Http\Requests\Company\Works\Quotations\SaveQuotationWorkRequest as SaveRequest;
use App\Http\Requests\Company\Works\TruncateWorksRequest as TruncateRequest;
use App\Http\Resources\Work\WorkResource;
use App\Repositories\Work\WorkRepository;

class QuotationWorkController extends Controller
{
    /**
     * Repository Container
     *
     * @var WorkRepository
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
        $works = WorkResource::apiCollection($works);

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

        $this->work->detachManyFrom($quotation, $workIds);
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
