<?php

namespace App\Traits;

use App\Models\Company\Company;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

trait CompanyInputRequest
{
    use InputRequest;

    /**
     * Company object
     *
     * @var Company|null
     */
    private ?Company $company = null;

    /**
     * Check th company permission
     *
     * @param mixed $permission
     * @param mixed $model
     * @param string $companyColumn
     * @return bool
     */
    public function checkCompanyPermission(
        mixed  $permission,
        mixed  $model,
        string $companyColumn = 'company_id'
    ): bool
    {
        $user = $this->user();

        return $user->hasCompanyPermission(
            $model->{$companyColumn},
            $permission
        );
    }

    /**
     * Get a Company
     *
     * @param bool $withTrashed
     * @return Company
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getCompany(bool $withTrashed = false): Company
    {
        // Company already exist
        if ($this->company instanceof Company) {
            return $this->company;
        }

        $user = $this->user()->fresh();
        $user->load([$user->role_name . '.company']);
        $role = $user->{$user->role_name};

        if (!$company = $role->company) {
            $company = $withTrashed ?
                Company::withTrashed()->find($role->company_id) :
                Company::find($role->company_id);
        }

        if (!$company instanceof Company) {
            abort(403, 'This user has no company yet, please register.');
        }

        return $this->company = $company;
    }

    /**
     * Get company rules
     *
     * @return array
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function ruleWithCompany(): array
    {
        $input = $this->onlyInRules();
        $input['company_id'] = $this->getCompany()->id;

        return $input;
    }
}
