<?php

namespace App\Http\Controllers\Api\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Works\SaveWorkRequest as SaveRequest;
use App\Http\Requests\Works\FindWorkRequest as FindRequest;
use App\Http\Requests\Works\DeleteWorkRequest as DeleteRequest;
use App\Http\Requests\Works\PopulateContractWorksRequest as ContractPopulateRequest;
use App\Http\Requests\Works\PopulateQuotationWorksRequest as QuotationPopulateRequest;

use App\Http\Resources\WorkResource;

use App\Repositories\WorkRepository;

class WorkController extends Controller
{
    private $work;

    public function __construct(WorkRepository $work)
    {
    	$this->work = $work;
    }

    public function quotationWorks(QuotationPopulateRequest $request)
    {
        $options = $request->options();

        $works = $this->work->all($options);
        $works = $this->work->paginate();
        $works = WorkResource::apiCollection($works);

        return response()->json(['works' => $works]);
    }

    public function contractWorks(ContractPopulateRequest $request)
    {
        $options = $request->options();

        $works = $this->work->all($options);
        $works = $this->work->paginate();
        $works = WorkResource::apiCollection($works);

        return response()->json(['works' => $works]);
    }

    public function store(SaveRequest $request)
    {
    	$input = $request->onlyInRules();
    	$work = $this->work->save($input);

    	return apiResponse($this->work);
    }

    public function update(SaveRequest $request)
    {
    	$work = $request->getWork();
    	$this->work->setModel($work);

    	$input = $request->onlyInRules();
    	$this->work->save($input);

    	return apiResponse($this->work);
    }

    public function delete(DeleteRequest $request)
    {
    	$work = $request->getWork();

    	$this->work->setModel($work);
    	$this->work->delete();

    	return apiResponse($this->work);
    }
}
