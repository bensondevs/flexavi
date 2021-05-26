<?php

namespace App\Http\Controllers\Api\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Works\SaveWorkRequest as SaveRequest;
use App\Http\Requests\Works\FindWorkRequest as FindRequest;
use App\Http\Requests\Works\PopulateCompanyWorksRequest as PopulateRequest;

use App\Repositories\WorkRepository;

class WorkController extends Controller
{
    private $work;

    public function __construct(WorkRepository $work)
    {
    	$this->work = $work;
    }

    public function companyWorks(PopulateRequest $request)
    {
    	$options = $request->options();
        
    	$works = $this->work->all($options);
    	$works = $this->work->paginate();
    	$works->data = WorkResource::collection($works);

    	return response()->json(['works' => $works]);
    }

    public function store(SaveRequest $request)
    {
    	$input = $request->onlyInRules();
    	$work = $this->work->save($input);

    	return apiResponse($this->work, ['work' => $work]);
    }

    public function update(SaveRequest $request)
    {
    	$work = $request->getWork();
    	$this->work->setModel($work);

    	$input = $request->onlyInRules();
    	$this->work->save($input);

    	return apiResponse($this->work, ['work' => $work]);
    }

    public function delete(FindRequest $request)
    {
    	$work = $request->getWork();

    	$this->work->setModel($work);
    	$this->work->delete();

    	return apiResponse($this->work);
    }
}
