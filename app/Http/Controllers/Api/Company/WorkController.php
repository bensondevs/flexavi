<?php

namespace App\Http\Controllers\Api\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Works\DeleteWorkRequest as DeleteRequest;
use App\Http\Requests\Works\PopulateFinishedWorksRequest as PopulateFinishedRequest;
use App\Http\Requests\Works\PopulateUnfinishedWorksRequest as PopulateUnfinishedRequest;
use App\Http\Requests\Works\PopulateCompanyWorksRequest as CompanyPopulateRequest;

use App\Http\Resources\WorkResource;

use App\Repositories\WorkRepository;

class WorkController extends Controller
{
    private $work;

    public function __construct(WorkRepository $work)
    {
    	$this->work = $work;
    }

    public function companyWorks(CompanyPopulateRequest $request)
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

    public function finishedWorks(PopulateFinishedRequest $request)
    {
        $options = $request->options();

        $works = $this->work->all($options);
        $works = $this->work->paginate();
        $works = WorkResource::apiCollection($works);

        return response()->json(['works' => $works]);
    }

    public function unfinishedWorks(PopulateUnfinishedRequest $request)
    {
        $options = $request->options();

        $works = $this->work->all($options);
        $works = $this->work->paginate();
        $works = WorkResource::apiCollection($works);

        return response()->json(['works' => $works]);
    }

    public function store(SaveRequest $request)
    {
        $input = $request->validated();
        $works = $this->work->save($input);

        return apiResponse($this->work);
    }

    public function execute(ExecuteRequest $request)
    {
        $work = $request->getWork();
        $this->work->setModel($work);

        $appointment = $request->getAppointment();
        $this->work->execute($appointment);

        return apiResponse($this->work);
    }

    public function process(ProcessRequest $request)
    {
        $work = $request->getWork();

        $this->work->setModel($work);
        $this->work->process();

        return apiResponse($this->work);
    }

    public function markFinished(MarkFinishRequest $request)
    {
        $work = $request->getWork();

        $this->work->setModel($work);
        $this->work->markFinished();

        return apiResponse($this->work);
    }

    public function markUnfinish(MarkUnfinishRequest $request)
    {
        $work = $request->getWork();
        $this->work->setModel($work);

        $unfinishData = $request->validated();
        $this->work->markUnfinish($unfinishData);

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
