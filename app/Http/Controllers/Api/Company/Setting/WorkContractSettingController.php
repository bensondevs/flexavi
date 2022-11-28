<?php

namespace App\Http\Controllers\Api\Company\Setting;

use App\Http\Controllers\Controller;
use App\Http\Requests\Company\WorkContractSettings\{PopulateWorkContractSettingRequest as PopulateRequest,
    UpdateWorkContractSettingRequest as UpdateRequest
};
use App\Http\Resources\Setting\WorkContractSettingResource;
use App\Services\Setting\WorkContract\WorkContractSettingService;
use App\Services\Template\WorkContract\WorkContractTemplateService;
use Illuminate\Http\JsonResponse;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class WorkContractSettingController extends Controller
{
    /**
     * Work contract setting service container variable
     *
     * @var WorkContractSettingService
     */
    private WorkContractSettingService $workContractSettingService;

    /**
     * Controller constructor method.
     *
     * @param WorkContractSettingService $workContractSettingService
     */
    public function __construct(WorkContractSettingService $workContractSettingService)
    {
        $this->workContractSettingService = $workContractSettingService;
    }

    /**
     * Update work contract setting
     *
     * @param UpdateRequest $request
     * @return JsonResponse
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function update(UpdateRequest $request): JsonResponse
    {
        $workContractSetting = $request->getWorkContractSetting();
        $service = $this->workContractSettingService->save($workContractSetting, $request->workContractSettingData());
        return apiResponse($service);
    }

    /**
     * Populate work contract setting
     *
     * @param PopulateRequest $request
     * @return JsonResponse
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function workContract(PopulateRequest $request): JsonResponse
    {
        $workContractSetting = $request->getWorkContractSetting();
        $workContractSetting = new WorkContractSettingResource($workContractSetting);
        return response()->json([
            'work_contract_setting' => $workContractSetting
        ]);
    }

    /**
     * Populate work contract setting
     *
     * @param PopulateRequest $request
     * @return JsonResponse
     */
    public function variables(PopulateRequest $request): JsonResponse
    {
        $variables = WorkContractTemplateService::CONFIGURATIONS;
        return response()->json([
            'variables' => $variables
        ]);
    }
}
