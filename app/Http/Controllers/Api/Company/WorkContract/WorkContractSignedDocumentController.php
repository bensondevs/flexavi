<?php

namespace App\Http\Controllers\Api\Company\WorkContract;

use App\Http\Controllers\Controller;
use App\Http\Requests\Company\WorkContracts\{RemoveSignedDocumentRequest as RemoveRequest,
    UploadSignedDocumentRequest as UploadRequest
};
use App\Repositories\WorkContract\WorkContractRepository;
use Illuminate\Http\JsonResponse;

class WorkContractSignedDocumentController extends Controller
{
    /**
     * Work contract repository class instance.
     *
     * @var WorkContractRepository
     */
    private WorkContractRepository $workContractRepository;

    /**
     * Controller constructor method.
     *
     * @param WorkContractRepository $workContractRepository
     */
    public function __construct(WorkContractRepository $workContractRepository)
    {
        $this->workContractRepository = $workContractRepository;
    }

    /**
     * Remove work contract signed document.
     *
     * @param RemoveRequest $request
     * @return JsonResponse
     */
    public function removeSignedDocument(RemoveRequest $request): JsonResponse
    {
        $this->workContractRepository->setModel($request->getWorkContract());
        $this->workContractRepository->removeSignedDocument();
        return apiResponse($this->workContractRepository);
    }

    /**
     * Upload work contract signed document.
     *
     * @param UploadRequest $request
     * @return JsonResponse
     */
    public function uploadSignedDocument(UploadRequest $request): JsonResponse
    {
        $this->workContractRepository->setModel($request->getWorkContract());
        $this->workContractRepository->uploadSignedDocument($request->file('signed_document'));
        return apiResponse($this->workContractRepository);
    }
}
