<?php

namespace App\Http\Controllers\Api\Company\Owner;

use App\Http\Controllers\Controller;
use App\Http\Requests\Company\Owners\DeleteOwnerRequest as DeleteRequest;
use App\Http\Requests\Company\Owners\FindOwnerRequest as FindRequest;
use App\Http\Requests\Company\Owners\MakeAsMainOwnerRequest as MainOwnerRequest;
use App\Http\Requests\Company\Owners\PopulateCompanyOwnersRequest as PopulateRequest;
use App\Http\Requests\Company\Owners\RestoreOwnerRequest as RestoreRequest;
use App\Http\Requests\Company\Owners\SaveOwnerRequest as SaveRequest;
use App\Http\Requests\Company\Owners\SetImageRequest;
use App\Http\Requests\Company\Owners\UpdateOwnerRequest as UpdateRequest;
use App\Http\Resources\Owner\OwnerResource;
use App\Repositories\Company\CompanyOwnerRepository;
use Illuminate\Http\JsonResponse;

class OwnerController extends Controller
{
    /**
     * Owner Repository Class Container
     *
     * @var CompanyOwnerRepository
     */
    private CompanyOwnerRepository $companyOwnerRepository;

    /**
     * Controller constructor method
     *
     * @param CompanyOwnerRepository $companyOwnerRepository
     */
    public function __construct(CompanyOwnerRepository $companyOwnerRepository)
    {
        $this->companyOwnerRepository = $companyOwnerRepository;
    }

    /**
     * Populate company owners
     *
     * @param PopulateRequest $request
     * @return JsonResponse
     */
    public function companyOwners(PopulateRequest $request): JsonResponse
    {
        $options = $request->options();

        $owners = $this->companyOwnerRepository->all($options);
        $owners = $this->companyOwnerRepository->paginate($options['per_page']);
        $owners = OwnerResource::apiCollection($owners);

        return response()->json(['owners' => $owners]);
    }

    /**
     * Populate company inviteable owners
     *
     * @param PopulateRequest $request
     * @return JsonResponse
     */
    public function inviteableOwners(PopulateRequest $request): JsonResponse
    {
        $options = $request->options();

        $owners = $this->companyOwnerRepository->inviteables($options);
        $owners = $this->companyOwnerRepository->paginate($options['per_page']);
        $owners = OwnerResource::apiCollection($owners);

        return response()->json(['owners' => $owners]);
    }

    /**
     * Populate company trashed owners
     *
     * @param PopulateRequest $request
     * @return JsonResponse
     */
    public function trashedOwners(PopulateRequest $request): JsonResponse
    {
        $options = $request->options();

        $owners = $this->companyOwnerRepository->trasheds($options);
        $owners = $this->companyOwnerRepository->paginate($options['per_page']);
        $owners = OwnerResource::apiCollection($owners);

        return response()->json(['owners' => $owners]);
    }

    /**
     * Store company owners
     * @param SaveRequest $request
     * @return JsonResponse
     * @deprecated
     */
//    public function store(SaveRequest $request): JsonResponse
//    {
//        $input = $request->ruleWithCompany();
//        $owner = $this->companyOwnerRepository->save($input);
//        $owner = new OwnerResource($owner->fresh());
//
//        return apiResponse($this->companyOwnerRepository, ['owner' => $owner]);
//    }

    /**
     * View company owners
     *
     * @param FindRequest $request
     * @return JsonResponse
     */
    public function view(FindRequest $request): JsonResponse
    {
        $owner = $request->getOwner();
        $owner = new OwnerResource($owner);

        return response()->json(['owner' => $owner]);
    }

    /**
     * Update company owners
     *
     * @param UpdateRequest $request
     * @return JsonResponse
     */
    public function update(UpdateRequest $request): JsonResponse
    {
        $owner = $request->getOwner();
        $owner = $this->companyOwnerRepository->setModel($owner);

        $input = $request->ruleWithCompany();
        $owner = $this->companyOwnerRepository->save($input);

        return apiResponse($this->companyOwnerRepository, ['owner' => $owner]);
    }

    /**
     * Delete company owners
     *
     * @param DeleteRequest $request
     * @return JsonResponse
     */
    public function delete(DeleteRequest $request): JsonResponse
    {
        $owner = $request->getOwner();
        $this->companyOwnerRepository->setModel($owner);

        $force = $request->input('force', false);
        $this->companyOwnerRepository->delete($force);

        return apiResponse($this->companyOwnerRepository);
    }

    /**
     * Restore company owners
     *
     * @param RestoreRequest $request
     * @return JsonResponse
     */
    public function restore(RestoreRequest $request): JsonResponse
    {
        $owner = $request->getTrashedOwner();
        $owner = $this->companyOwnerRepository->setModel($owner);
        $owner = $this->companyOwnerRepository->restore();
        $owner = new OwnerResource($owner);

        return apiResponse($this->companyOwnerRepository, ['owner' => $owner]);
    }

    /**
     * Make as main owner
     *
     * @param MainOwnerRequest $request
     * @return JsonResponse
     */
    public function makeAsMainOwner(MainOwnerRequest $request): JsonResponse
    {
        $owner = $request->getOwner();
        $owner = $this->companyOwnerRepository->setModel($owner);
        $owner = $this->companyOwnerRepository->replacePrime();
        $owner = new OwnerResource($owner->fresh());

        return apiResponse($this->companyOwnerRepository, [
            'owner' => $owner
        ]);
    }

    /**
     * Set image
     *
     * @param SetImageRequest $request
     * @return JsonResponse
     */
    public function setImage(SetImageRequest $request)
    {
        $this->companyOwnerRepository->setModel($request->getOwner());
        $this->companyOwnerRepository->setImage($request->file('image'));
        return apiResponse($this->companyOwnerRepository);
    }
}
