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

    public function finish(FinishRequest $request)
    {
        $executeWork = $request->getExecuteWork();
        $this->execute->setModel($executeWork);

        $finishData = $request->validated();
        $this->execute->finish($finishData);

        return apiResponse($this->execute);
    }

    public function delete(DeleteRequest $request)
    {
        $executeWork = $request->getExecuteWork();
        $this->execute->setModel($executeWork);

        $force = $request->input('force');
        $this->execute->delete($force);

        return apiResponse($this->execute);
    }
}
