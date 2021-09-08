<?php

namespace App\Http\Controllers\Api\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\ExecuteWorks\PopulateExecuteWorksRequest as PopulateRequest;
use App\Http\Requests\ExecuteWorks\ExecuteWorkRequest as ExecuteRequest;
use App\Http\Requests\ExecuteWorks\MarkUnfinishedWorkRequest as MarkUnfinishedRequest;
use App\Http\Requests\ExecuteWorks\MarkFinishedWorkRequest as MarkFinishedRequest;
use App\Http\Requests\ExecuteWorks\MakeExecuteWorkContinuationRequest as MakeContinuationRequest;
use App\Http\Requests\ExecuteWorks\DeleteExecuteWorkRequest as DeleteRequest;
use App\Http\Requests\ExecuteWorks\RestoreExecuteWorkRequest as RestoreRequest;

use App\Http\Resources\ExecuteWorkResource;

use App\Repositories\ExecuteWorkRepository;

class ExecuteWorkController extends Controller
{
    private $execute;

    public function __construct(ExecuteWorkRepository $execute)
    {
        $this->execute = $execute;
    }

    public function executeWorks(PopulateRequest $request)
    {
        $options = $request->options();

        $executeWorks = $this->execute->all($options, true);
        $executeWorks = ExecuteWorkResource::apiCollection($executeWorks);

        return response()->json(['execute_works' => $executeWorks]);
    }

    public function trashedExecuteWorks(PopulateRequest $request)
    {
        $options = $request->options();

        $executeWorks = $this->execute->trasheds($options, true);
        $executeWorks = ExecuteWorkResource::apiCollection($executeWorks);

        return response()->json(['execute_works' => $executeWorks]);
    }

    public function execute(ExecuteRequest $request)
    {
        $input = $request->executeData();
        $this->execute->execute($input);

        return apiResponse($this->execute);
    }

    public function markFinished(MarkFinishedRequest $request)
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

    public function restore(RestoreRequest $request)
    {
        $executeWork = $request->getExecuteWork();

        $this->execute->setModel($executeWork);
        $this->execute->restore();

        return apiResponse($this->execute);
    }
}
