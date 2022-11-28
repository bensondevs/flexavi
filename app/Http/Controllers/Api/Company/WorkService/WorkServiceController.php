<?php

namespace App\Http\Controllers\Api\Company\WorkService;

use App\Http\Controllers\Controller;
use App\Http\Requests\Company\WorkServices\{ChangeStatusWorkServiceRequest as ChangeStatusRequest,
    DeleteWorkServiceRequest as DeleteRequest,
    FindWorkServiceRequest as FindRequest,
    PopulateCompanyWorkServicesRequest as PopulateRequest,
    RestoreWorkServiceRequest as RestoreRequest,
    SaveWorkServiceRequest as SaveRequest,
};
use App\Http\Resources\WorkService\WorkServiceResource;
use App\Repositories\WorkService\WorkServiceRepository;
use Illuminate\Http\JsonResponse;

class WorkServiceController extends Controller
{
    /**
     * Work Service repository container variable
     *
     * @var WorkServiceRepository
     */
    private WorkServiceRepository $workServiceRepository;

    /**
     * Controller constructor method
     *
     * @param WorkServiceRepository $workServiceRepository
     * @return void
     */
    public function __construct(WorkServiceRepository $workServiceRepository)
    {
        $this->workServiceRepository = $workServiceRepository;
    }

    /**
     * Populate company work services
     *
     * @param PopulateRequest $request
     * @return JsonResponse
     */
    public function companyWorkServices(PopulateRequest $request): JsonResponse
    {
        $options = $request->options();
        $workServices = $this->workServiceRepository->all($options, true);
        $workServices = WorkServiceResource::apiCollection($workServices);
        return response()->json(['work_services' => $workServices]);
    }

    /**
     * View company work service
     *
     * @param FindRequest $request
     * @return JsonResponse
     */
    public function view(FindRequest $request): JsonResponse
    {
        $workService = $request->getWorkService();
        $workService = new WorkServiceResource($workService);
        return response()->json(['work_service' => $workService]);
    }

    /**
     * Store company work service
     *
     * @param SaveRequest $request
     * @return JsonResponse
     */
    public function store(SaveRequest $request): JsonResponse
    {
        $workService = $this->workServiceRepository->save($request->validated());
        return apiResponse($this->workServiceRepository, [
            'work_service' => new WorkServiceResource($workService)
        ]);
    }

    /**
     * Update company work service
     *
     * @param SaveRequest $request
     * @return JsonResponse
     */
    public function update(SaveRequest $request): JsonResponse
    {
        $workService = $request->getWorkService();
        $this->workServiceRepository->setModel($workService);
        $workService = $this->workServiceRepository->save($request->validated());
        return apiResponse($this->workServiceRepository, [
            'work_service' => new WorkServiceResource($workService)
        ]);
    }

    /**
     * Delete Work Service
     *
     * @param DeleteRequest $request
     * @return JsonResponse
     */
    public function delete(DeleteRequest $request): JsonResponse
    {
        $workService = $request->getWorkService();
        $this->workServiceRepository->setModel($workService);
        $force = strtobool($request->input('force'));
        $this->workServiceRepository->delete($force);
        return apiResponse($this->workServiceRepository);
    }

    /**
     * Populate company soft-deleted work services
     *
     * @param PopulateRequest $request
     * @return JsonResponse
     */
    public function trashedWorkServices(PopulateRequest $request): JsonResponse
    {
        $options = $request->options();
        $workServices = $this->workServiceRepository->trasheds($options, true);
        $workServices = WorkServiceResource::apiCollection($workServices);
        return response()->json(['work_services' => $workServices]);
    }

    /**
     * Populate company active work services
     *
     * @param PopulateRequest $request
     * @return JsonResponse
     */
    public function activeWorkServices(PopulateRequest $request): JsonResponse
    {
        $options = $request->options();
        $workServices = $this->workServiceRepository->active($options, true);
        $workServices = $this->workServiceRepository->paginate();
        $workServices = WorkServiceResource::apiCollection($workServices);
        return response()->json(['work_services' => $workServices]);
    }

    /**
     * Populate company inactive work services
     *
     * @param PopulateRequest $request
     * @return JsonResponse
     */
    public function inActiveWorkServices(PopulateRequest $request): JsonResponse
    {
        $options = $request->options();
        $workServices = $this->workServiceRepository->inactive($options, true);
        $workServices = $this->workServiceRepository->paginate();
        $workServices = WorkServiceResource::apiCollection($workServices);
        return response()->json(['work_services' => $workServices]);
    }

    /**
     * Restore car
     *
     * @param ChangeStatusRequest $request
     * @return JsonResponse
     */
    public function changeStatus(ChangeStatusRequest $request): JsonResponse
    {
        $workService = $request->getWorkService();
        $workService = $this->workServiceRepository->setModel($workService);
        $workService = $this->workServiceRepository->changeStatus($request->getChangeStatus());
        return apiResponse($this->workServiceRepository, [
            'work_service' => new WorkServiceResource($workService)
        ]);
    }

    /**
     * Restore car
     *
     * @param RestoreRequest $request
     * @return JsonResponse
     */
    public function restore(RestoreRequest $request): JsonResponse
    {
        $workService = $request->getTrashedWorkService();
        $workService = $this->workServiceRepository->setModel($workService);
        $workService = $this->workServiceRepository->restore();
        return apiResponse($this->workServiceRepository, [
            'work_service' => new WorkServiceResource($workService)
        ]);
    }
}
