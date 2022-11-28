<?php

namespace App\Http\Controllers\Api\Company\WorkContract;

use App\Http\Controllers\Controller;
use App\Http\Requests\Company\WorkContracts\{DeleteWorkContractRequest as DeleteRequest,
    DraftWorkContractRequest as DraftRequest,
    FindWorkContractRequest as FindRequest,
    NullifyWorkContractRequest as NullifyRequest,
    PopulateWorkContractsRequest as PopulateRequest,
    PrintWorkContractRequest as PrintRequest,
    RestoreWorkContractRequest as RestoreRequest,
    SendWorkContractRequest as SendRequest,
    SetAsDefaultFormatRequest as SetDefaultRequest,
    UseCompanyFormatRequest
};
use App\Http\Resources\WorkContract\WorkContractFormatting\WorkContractResource as WorkContractFormattingResource;
use App\Http\Resources\WorkContract\WorkContractResource;
use App\Repositories\WorkContract\WorkContractRepository;
use App\Services\Setting\WorkContract\WorkContractSettingService;
use App\Services\Template\WorkContract\WorkContractTemplateService;
use App\Services\WorkContract\WorkContractService;
use Illuminate\Http\JsonResponse;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class WorkContractController extends Controller
{
    /**
     * Work Contract Repository class
     *
     * @var WorkContractRepository
     */
    private WorkContractRepository $workContractRepository;

    /**
     * Work Contract Service class
     *
     * @var  WorkContractService
     */
    private WorkContractService $workContractService;

    /**
     * Work Contract Setting Service class
     *
     * @var WorkContractSettingService
     */
    private WorkContractSettingService $workContractSettingService;

    /**
     * Controller constructor method
     *
     * @param WorkContractRepository $workContractRepository
     * @param WorkContractService $workContractService
     * @param WorkContractSettingService $workContractSettingService
     */
    public function __construct(
        WorkContractRepository     $workContractRepository,
        WorkContractService        $workContractService,
        WorkContractSettingService $workContractSettingService
    )
    {
        $this->workContractRepository = $workContractRepository;
        $this->workContractService = $workContractService;
        $this->workContractSettingService = $workContractSettingService;
    }

    /**
     * Populate company work contracts
     *
     * @param PopulateRequest $request
     * @return JsonResponse
     */
    public function companyWorkContracts(PopulateRequest $request): JsonResponse
    {
        $workContracts = $this->workContractRepository->all($request->options(), true);
        $workContracts = WorkContractResource::apiCollection($workContracts);
        return response()->json(['work_contracts' => $workContracts]);
    }

    /**
     * Populate trashed company work contracts
     *
     * @param PopulateRequest $request
     * @return JsonResponse
     */
    public function trashedWorkContracts(PopulateRequest $request)
    {
        $workContracts = $this->workContractRepository->trasheds($request->options(), true);
        $workContracts = WorkContractResource::apiCollection($workContracts);
        return response()->json(['work_contracts' => $workContracts]);
    }

    /**
     * View work contract
     *
     * @param FindRequest $request
     * @return JsonResponse
     */
    public function view(FindRequest $request): JsonResponse
    {
        $workContract = $request->getWorkContract();
        return response()->json(['work_contract' => new WorkContractResource($workContract)]);
    }

    /**
     * View work contract
     *
     * @param FindRequest $request
     * @return JsonResponse
     */
    public function preview(FindRequest $request)
    {
        $workContract = $request->getWorkContract();
        return response()->json([
            'work_contract' => new WorkContractFormattingResource($workContract)
        ]);
    }

    /**
     * restore company work contract
     *
     * @param RestoreRequest $request
     * @return JsonResponse
     */
    public function restore(RestoreRequest $request)
    {
        $workContract = $request->getWorkContract(true);
        $this->workContractRepository->setModel($workContract);
        $this->workContractRepository->restore();

        return apiResponse($this->workContractRepository);
    }

    /**
     * Delete company work contract
     *
     * @param DeleteRequest $request
     * @return JsonResponse
     */
    public function delete(DeleteRequest $request)
    {
        $workContract = $request->getWorkContract(true);
        $this->workContractRepository->setModel($workContract);
        $this->workContractRepository->delete($request->has("force"));

        return apiResponse($this->workContractRepository);
    }

    /**
     * Delete company work contract
     *
     * @param PrintRequest $request
     * @return JsonResponse
     */
    public function print(PrintRequest $request)
    {
        $workContract = $request->getWorkContract();
        $this->workContractRepository->setModel($workContract);
        $this->workContractRepository->print();

        return apiResponse($this->workContractRepository);
    }

    /**
     * Set work contract as draft
     *
     * @param DraftRequest $request
     * @return JsonResponse
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function draft(DraftRequest $request)
    {
        $workContract = $request->has('work_contract_id') ? $request->getWorkContract() : null;
        $service = $this->workContractService->save($workContract, $request->workContractData());
        return apiResponse($service);
    }

    /**
     * @param SendRequest $request
     * @return JsonResponse
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function send(SendRequest $request)
    {
        if (!$request->has('work_contract_id')) {
            return apiResponse($this->workContractService->save(null, $request->workContractData()));

        }
        $workContract = $request->getWorkContract();
        // Send work contract
        if ($workContract->isDrafted()) {
            return apiResponse($this->workContractService->save($workContract, $request->workContractData()));
        }
        return apiResponse($this->workContractService->resend($workContract));

    }

    /**
     * Set work contract as default format
     *
     * @param SetDefaultRequest $request
     * @return JsonResponse
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function setAsDefaultFormat(SetDefaultRequest $request)
    {
        $setting = $request->getWorkContractSetting();
        $workContract = null;
        if ($request->has('work_contract_id')) {
            $workContract = $request->getWorkContract();
        }
        $workContractData = $request->validated();
        $service = $this->workContractSettingService->setWorkContractAsDefaultFormat($setting, $workContract, $workContractData);
        return apiResponse($service);
    }

    /**
     * Populate work contract variables
     *
     * @return JsonResponse
     */
    public function variables()
    {
        $variables = array_keys(WorkContractTemplateService::CONFIGURATIONS);
        return response()->json(['variables' => $variables]);
    }

    /**
     * Nullify work contract
     *
     * @param NullifyRequest $request
     * @return JsonResponse
     */
    public function nullify(NullifyRequest $request)
    {
        $workContract = $request->getWorkContract();
        $this->workContractRepository->setModel($workContract);
        $this->workContractRepository->nullify();

        return apiResponse($this->workContractRepository);
    }

    /**
     * Update work contract to default format company
     *
     * @param UseCompanyFormatRequest $request
     * @return JsonResponse
     */
    public function useCompanyFormat(UseCompanyFormatRequest $request)
    {
        $workContract = $request->getWorkContract();
        $service = $this->workContractService->useCompanyFormat($workContract);
        return apiResponse($service);
    }
}
