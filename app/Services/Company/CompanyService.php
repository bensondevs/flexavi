<?php

namespace App\Services\Company;

use App\Models\Company\Company;
use App\Models\User\User;
use App\Repositories\Company\CompanyRepository;

/**
 * @see \Tests\Unit\Services\Company\CompanyService\CompanyServiceTest
 *      To the service class unit tester class.
 */
class CompanyService
{
    /**
     * Company repository.
     *
     * @var CompanyRepository
     */
    private CompanyRepository $companyRepository;

    /**
     * Create company service instance.
     *
     * @param CompanyRepository $companyRepository
     */
    public function __construct(CompanyRepository $companyRepository)
    {
        $this->companyRepository = $companyRepository;
    }

    /**
     * Handle owner registered.
     *
     * This method will be called when owner registered, and will create a company for him
     *
     * @param User $user
     * @return ?Company
     */
    public function ownerRegistered(User $user): ?Company
    {
        return $this->companyRepository->save([
            'company_name' => $user->fullname,
            'email' => $user->email,
        ]);
    }
}
