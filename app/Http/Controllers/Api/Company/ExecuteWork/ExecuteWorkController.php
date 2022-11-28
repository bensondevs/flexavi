<?php

namespace App\Http\Controllers\Api\Company\ExecuteWork;

use App\Http\Controllers\Api\Company\Illuminate;
use App\Http\Controllers\Controller;
use App\Http\Requests\Company\ExecuteWorks\{DeleteExecuteWorkRequest as DeleteRequest};
use App\Http\Requests\Company\ExecuteWorks\FindExecuteWorkRequest as FindRequest;
use App\Http\Requests\Company\ExecuteWorks\MarkFinishedWorkRequest as MarkFinishedRequest;
use App\Http\Requests\Company\ExecuteWorks\PopulateCustomerExecuteWorksRequest as CustomerPopulateRequest;
use App\Http\Requests\Company\ExecuteWorks\PopulateExecuteWorksRequest as PopulateRequest;
use App\Http\Requests\Company\ExecuteWorks\RestoreExecuteWorkRequest as RestoreRequest;
use App\Http\Requests\Company\ExecuteWorks\SaveExecuteWorkRequest as SaveRequest;
use App\Http\Resources\ExecuteWork\ExecuteWorkResource;
use App\Repositories\ExecuteWork\ExecuteWorkPhotoRepository;
use App\Repositories\ExecuteWork\ExecuteWorkRelatedMaterialRepository;
use App\Repositories\ExecuteWork\ExecuteWorkRepository;
use DB;

class ExecuteWorkController extends Controller
{
    /**
     * Execute work repsitory class container
     *
     * @var ExecuteWorkRepository
     */
    private $execute;

    /**
     * Execute work photo repsitory class container
     *
     * @var ExecuteWorkPhotoRepository
     */
    private $executeWorkPhoto;

    /**
     * Execute work related material repsitory class container
     *
     * @var ExecuteWorkRelatedMaterialRepository
     */
    private $executeWorkRelatedMaterial;

    /**
     * Controller constructor method
     *
     * @param ExecuteWorkRepository $execute
     * @param ExecuteWorkPhotoRepository $executeWorkPhoto
     * @param ExecuteWorkRelatedMaterialRepository $executeWorkRelatedMaterial
     */
    public function __construct(
        ExecuteWorkRepository                $execute,
        ExecuteWorkPhotoRepository           $executeWorkPhoto,
        ExecuteWorkRelatedMaterialRepository $executeWorkRelatedMaterial
    )
    {
        $this->execute = $execute;
        $this->executeWorkPhoto = $executeWorkPhoto;
        $this->executeWorkRelatedMaterial = $executeWorkRelatedMaterial;
    }

    /**
     * Populate execute works
     *
     * @param PopulateRequest $request
     * @return Illuminate\Support\Facades\Response
     */
    public function companyExecuteWorks(PopulateRequest $request)
    {
        $options = $request->options();

        $executeWorks = $this->execute->all($options, true);
        $executeWorks = ExecuteWorkResource::apiCollection($executeWorks);

        return response()->json(['execute_works' => $executeWorks]);
    }

    /**
     * Populate with trashed execute works
     *
     * @param PopulateRequest $request
     * @return Illuminate\Support\Facades\Response
     */
    public function customerExecuteWorks(CustomerPopulateRequest $request)
    {
        $options = $request->options();

        $executeWorks = $this->execute->all($options, true);
        $executeWorks = ExecuteWorkResource::apiCollection($executeWorks);

        return response()->json(['execute_works' => $executeWorks]);
    }

    /**
     * Populate with trashed execute works
     *
     * @param PopulateRequest $request
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
     * view execute work
     *
     * @param PopulateRequest $request
     * @return Illuminate\Support\Facades\Response
     */
    public function view(FindRequest $request)
    {
        $executeWork = $request->getExecuteWork();
        return response()->json(['execute_work' => new ExecuteWorkResource($executeWork)]);
    }

    /**
     * Store execute work
     *
     * @param SaveRequest $request
     * @return Illuminate\Support\Facades\Response
     */
    public function store(SaveRequest $request)
    {
        DB::beginTransaction();
        $executeWork = $this->execute->save($request->executeWorkData());
        $this->executeWorkRelatedMaterial->setModel($executeWork->relatedMaterial);
        $this->executeWorkRelatedMaterial->save($request->executeWorkRelatedMaterialData());

        $this->executeWorkPhoto->save($request->executeWorkPhotosData(), $executeWork);
        DB::commit();
        return apiResponse($this->execute, $executeWork);
    }


    /**
     * Mark execute work finished
     *
     * @param MarkFinishedRequest $request
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
     * @param DeleteRequest $request
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
     * @param RestoreRequest $request
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
