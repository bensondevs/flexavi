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

        // ID is already set
        $id = $this->input('company_id');
        if ($id) return Company::findOrFail($id);

        // None exist
        $user = $this->user();
        return $user->{$user->user_role}->company;
    }

    public function authorizeCompanyAction(
        string $actionObject, 
        $companyColumn = 'company_id'
    )
    {
        $user = $this->user();
        $company = $this->getCompany();

        $actionName = ($this->isMethod('POST')) ? 'create' : 'edit';
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