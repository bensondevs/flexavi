<?php

namespace App\Traits;

use App\Traits\InputRequest;

trait CompanyInputRequest 
{
    use InputRequest;

    private $company;

    public function getCompany()
    {
        // Company already exist
        if ($this->company) return $this->company;

        // If middleware has company
        if ($requestCompany = request()->get('_company')) {
            return $this->company = $requestCompany;
        }

        // ID is already set
        if ($id = $this->input('company_id')) 
            return $this->company = Company::findOrFail($id);

        // None exist
        $user = $this->user();
        if (! $company = $user->{$user->user_role}->company) {
            $message = 'This user has no company yet, please register.';
            return response()->json(['message' => $message], 403);
        }

        return $this->company = $company;
    }

    public function checkCompanyPermission(
        $permission, 
        $model, 
        $companyColumn = 'company_id'
    )
    {
        $user = $this->user();

        return $user->hasCompanyPermission(
            $model->{$companyColumn}, 
            $permission
        );
    }

    public function authorizeCompanyAction(
        string $actionObject, 
        $companyColumn = 'company_id'
    )
    {
        $user = $this->user();
        $company = $this->getCompany();

        $actionName = ($this->isMethod('POST')) ? 'create' : 'edit';

        if (! $this->model) {
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

    public function ruleWithCompany()
    {
    	$input = $this->onlyInRules();
    	$input['company_id'] = $this->getCompany()->id;

    	return $input;
    }
}