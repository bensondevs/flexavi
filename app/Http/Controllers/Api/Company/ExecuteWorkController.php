<?php

namespace App\Http\Controllers\Api\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\ExecuteWorks\{
    PopulateExecuteWorksRequest as PopulateRequest,
    ExecuteWorkRequest as ExecuteRequest,
    MarkUnfinishedWorkRequest as MarkUnfinishedRequest,
    MarkFinishedWorkRequest as MarkFinishedRequest,
    MakeExecuteWorkContinuationRequest as MakeContinuationRequest,
    DeleteExecuteWorkRequest as DeleteRequest,
    RestoreExecuteWorkRequest as RestoreRequest
};

use App\Http\Resources\ExecuteWorkResource;

use App\Repositories\ExecuteWorkRepository;

class ExecuteWorkController extends Controller
{
    /**
     * Execute work repsitory class container
     * 
     * @var \App\Repositories\ExecuteWorkRepository
     */
    private $execute;

    /**
     * Controller constructor method
     * 
     * @param \App\Repositories\ExecuteWorkRepository  $execute
     */
    public function __construct(ExecuteWorkRepository $execute)
    {
        $this->execute = $execute;
    }

    /**
     * Populate execute works
     * 
     * @param PopulateRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function executeWorks(PopulateRequest $request)
    {
        $options = $request->options();

        $executeWorks = $this->execute->all($options, true);
        $executeWorks = ExecuteWorkResource::apiCollection($executeWorks);

        return response()->json(['execute_works' => $executeWorks]);
    }

    /**
     * Populate with trashed execute works
     * 
     * @param PopulateRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function trashedExecuteWorks(PopulateRequest $request)
    {
        $options = $request->options();

        $executeWorks = $this->execute->trasheds($options, true);
        $executeWorks = ExecuteWorkResource::apiCollection($executeWorks);

        return response()->json(['execute_works' => $executeWorks]);
    }

    /**
     * Execute the work and create execute work record
     * 
     * @param ExecuteRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function execute(ExecuteRequest $request)
    {
        $input = $request->executeData();
        $this->execute->execute($input);

        return apiResponse($this->execute);
    }

    /**
     * Mark execute work finished
     * 
     * @param MarkFinishedRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function markFinished(MarkFinishedRequest $request)
    {
        $executeWork = $request->getExecuteWork();
        $this->execute->setModel($executeWork);

        $finishData = $request->validated();
        $this->execute->finish($finishData);

        return apiResponse($this->execute);
    }

    /**
     * Delete execute work log
     * 
     * @param DeleteRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function delete(DeleteRequest $request)
    {
        $executeWork = $request->getExecuteWork();
        $this->execute->setModel($executeWork);

        $force = $request->input('force');
        $this->execute->delete($force);

        return apiResponse($this->execute);
    }

    /**
     * Restore deleted execute work
     * 
     * @param RestoreRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function restore(RestoreRequest $request)
    {
        $executeWork = $request->getExecuteWork();

        $this->execute->setModel($executeWork);
        $this->execute->restore();

        return apiResponse($this->execute);
    }
}
