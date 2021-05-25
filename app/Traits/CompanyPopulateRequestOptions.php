<?php

namespace App\Traits;

use App\Traits\PopulateRequestOptions;

use App\Models\Company;

trait CompanyPopulateRequestOptions 
{
    use PopulateRequestOptions;

    private $company;

    public function getCompany()
    {
        $user = $this->user();

        return $this->company = $this->model = $this->company ?:
            $user->{$user->roles->first()->name}->company;
    }

    public function collectCompanyOptions()
    {
    	$this->addWhere([
    		'column' => 'company_id',
    		'value' => $this->getCompany()->id,
    	]);

    	return $this->collectOptions();
    }
}