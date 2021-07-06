<?php

namespace App\Http\Controllers\Api\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\ExecuteWorks\ExecuteWorkRequest as ExecuteRequest;
use App\Http\Requests\ExecuteWorks\MarkUnfinishedWorkRequest as MarkUnfinishedRequest;
use App\Http\Requests\ExecuteWorks\MarkFinishedWorkRequest as MarkFinishedRequest;
use App\Http\Requests\ExecuteWorks\MakeExecuteWorkContinuationRequest as MakeContinuationRequest;

use App\Repositories\ExecuteWorkRepository;

class ExecuteWorkController extends Controller
{
    private $execute;

    public function __construct(ExecuteWorkRepository $execute)
    {
        $this->execute = $execute;
    }

    public function execute(ExecuteRequest $request)
    {
        $input = $request->executionData();
        $this->execute->execute($input);

        return apiResponse($this->execute);
    }

    public function markUnfinished(MarkUnfinishedRequest $request)
    {
        $execute = $request->getExecuteWork();
        $this->execute->setModel($execute);

        $input = $request->unfinishData();
        $this->execute->markUnfinished($input);

        return apiResponse($this->execute);
    }

    public function markFinished(MarkFinishedRequest $request)
    {
        $execute = $request->getExecuteWork();
        $this->execute->setModel($execute);

        $input = $request->finishData();
        $this->execute->markFinsihed($input);

        return apiResponse($this->execute);
    }

    public function makeContinuation(MakeContinuationRequest $request)
    {
        $execute = $request->getExecuteWork();
        $this->execute->setModel($execute);

        $input = $request->onlyInRules();
        $this->execute->makeContinuation($input);

        return apiResponse($this->execute);
    }
}
