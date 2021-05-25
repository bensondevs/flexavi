<?php

namespace App\Traits;

use App\Traits\InputRequest;

trait CompanyInputRequest 
{
    use InputRequest;

    private $company;

    public function getCompany()
    {
        return $this->company = $this->model = $this->company ?:
            $this->getUser()
                ->{$this->getUser()->role->name}
                ->company;
    }

    public function authorizeCompanyAction(
        string $actionObject, 
        $companyColumn = 'company_id'
    )
    {
        $user = $this->user();
        $company = $this->getCompany();

        $actionName = ($this->isMethod('POST')) ? 'create' : 'edit';
        $authorizeAction = $user->hasCompanyPermission(
            $company->id, 
            $actionName . ' ' . $actionObject
        );

        if ($this->isMethod('POST')) return $authorizeAction;

        $authorizeRecord = ($company->id == $this->model->{$companyColumn});
        return $authorizeAction && $authorizeRecord;
    }

    public function ruleWithCompany()
    {
    	$input = $this->onlyInRules();
    	$input['company_id'] = $this->getCompany()->id;

    	return $input;
    }
}