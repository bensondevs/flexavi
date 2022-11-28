<?php

namespace App\Http\Controllers\Api\Company;

use App\Http\Controllers\Controller;
use App\Http\Requests\Company\{FindCompanyRequest as FindRequest,
    SelfCompanyRequest,
    StoreCompanyRequest as StoreRequest,
    UpdateCompanyRequest as UpdateRequest,
    UploadCompanyLogoRequest as UploadLogoRequest};
use App\Http\Resources\Company\CompanyResource;
use App\Repositories\{Company\CompanyOwnerRepository, Company\CompanyRepository};
use Illuminate\Http\JsonResponse;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class CompanyController extends Controller
{
    /**
     * Company Repository Class Container
     *
     * @var CompanyRepository
     */
    protected CompanyRepository $companyRepository;

    /**
     * Owner Repository Class Container
     *
     * @var CompanyOwnerRepository
     */
    protected CompanyOwnerRepository $ownerRepository;

    /**
     * Controller Constructor Method
     *
     * @param CompanyRepository $company
     * @param CompanyOwnerRepository $owner
     * @return void
     */
    public function __construct(
        CompanyRepository      $company,
        CompanyOwnerRepository $owner
    )
    {
        $this->companyRepository = $company;
        $this->ownerRepository = $owner;
    }

    /**
     * Store a new company
     *
     * @param StoreRequest $request
     * @return JsonResponse
     * @see \Tests\Feature\Dashboard\Company\Company\CompanyTest::test_store()
     *     to controller's feature test
     */
    public function store(StoreRequest $request): JsonResponse
    {
        $company = $this->companyRepository->save($request->validated());

        $this->ownerRepository->setModel($request->user()->owner);
        $this->ownerRepository->assignCompany($company);

        $company = new CompanyResource($company);
        return apiResponse($this->companyRepository, [
            'company' => $company,
        ]);
    }

    /**
     * View current user company
     *
     * @param SelfCompanyRequest $request
     * @return JsonResponse
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @see \Tests\Feature\Dashboard\Company\Company\CompanyTest::test_view_current_user_company()
     *   to controller's feature test
     */
    public function self(SelfCompanyRequest $request): JsonResponse
    {
        $company = $request->getCompany();
        $company = is_null($company) ? null : new CompanyResource($company);
        return response()->json([
            "company" => $company,
            "has_company" => boolval($company),
        ]);
    }

    /**
     * Upload the company's logo image
     *
     * @param UploadLogoRequest $request
     * @return JsonResponse
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @see \Tests\Feature\Dashboard\Company\Company\CompanyTest::test_upload_logo()
     *   to controller's feature test
     */
    public function uploadLogo(UploadLogoRequest $request): JsonResponse
    {
        $this->companyRepository->setModel($request->getCompany());
        $company = $this->companyRepository->uploadLogo($request->file("logo"));
        $company = new CompanyResource($company);
        return apiResponse($this->companyRepository, [
            'company' => $company,
        ]);
    }


    /**
     * Update a company
     *
     * @param UpdateRequest $request
     * @return JsonResponse
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @see \Tests\Feature\Dashboard\Company\Company\CompanyTest::test_update()
     *   to controller's feature test
     */
    public function update(UpdateRequest $request): JsonResponse
    {
        $this->companyRepository->setModel($request->getCompany());
        $this->companyRepository->save($request->validated());
        return apiResponse($this->companyRepository);
    }

    /**
     * Delete a company
     *
     * @param FindRequest $request
     * @return JsonResponse
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @see \Tests\Feature\Dashboard\Company\Company\CompanyTest::test_soft_delete()
     * @see \Tests\Feature\Dashboard\Company\Company\CompanyTest::test_hard_delete()
     *   to controller's feature test
     */
    public function delete(FindRequest $request): JsonResponse
    {
        $this->companyRepository->setModel($request->getCompany());
        $this->companyRepository->delete($request->input('force'));

        return apiResponse($this->companyRepository);
    }

    /**
     * Delete a company
     *
     * @param FindRequest $request
     * @return JsonResponse
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @see \Tests\Feature\Dashboard\Company\Company\CompanyTest::test_store()
     *   to controller's feature test
     */
    public function restore(FindRequest $request): JsonResponse
    {
        $this->companyRepository->setModel($request->getCompany());
        $this->companyRepository->restore();

        return apiResponse($this->companyRepository);
    }
}
