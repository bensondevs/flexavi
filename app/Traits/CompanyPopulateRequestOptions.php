<?php

namespace App\Traits;

use App\Models\Company\Company;

trait CompanyPopulateRequestOptions
{
    use PopulateRequestOptions;

    /**
     * Company object
     *
     * @var Company|null
     */
    private ?Company $company = null;

    /**
     * Check the company permission
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
     * Authorize a company action
     *
     * @param string $actionObject
     * @param string $companyColumn
     * @return bool
     */
    public function authorizeCompanyAction(
        string $actionObject,
        string $companyColumn = 'company_id'
    ): bool
    {
        $user = $this->user();
        $company = $this->getCompany();
        $actionName = $this->isMethod('POST') ? 'create' : 'edit';
        if (!$this->model) {
            return $user->hasCompanyPermission(
                $company->id,
                $actionName . ' ' . $actionObject
            );
        }

        return $user->hasCompanyPermission(
            $this->model->{$companyColumn} ?: $this->input($companyColumn),
            $actionName . ' ' . $actionObject
        );
    }

    /**
     * Get a Company
     *
     * @param bool $withTrashed
     * @return Company
     */
    public function getCompany($withTrashed = false)
    {
        // Company already exist
        if ($this->company) {
            return $this->company;
        }

        // If middleware has company
        if ($requestCompany = request()->get('_company')) {
            return $this->company = $requestCompany;
        }

        // ID is already set
        if ($id = $this->input('company_id')) {
            return $this->company = Company::withTrashed($withTrashed)->find($id);
        }

        // None exist
        try {
            $user = $this->user()->fresh();
            $role = $user->{$user->role_name};

            $company = $withTrashed ?
                $role->company()->withTrashed()->first() : $role->company;

            return $this->company = $company;
        } catch (\Exception $e) {
            return abort(403, 'This user has no company yet, please register.');
        }

        return $this->company = $company;
    }

    /**
     * Collect the company options
     *
     * @return array
     */
    public function collectCompanyOptions(): array
    {
        $this->addWhere([
            'clause' => 'where',
            'column' => 'company_id',
            'operator' => '=',
            'value' => $this->getCompany()->id,
        ]);

        return $this->collectOptions();
    }
}
