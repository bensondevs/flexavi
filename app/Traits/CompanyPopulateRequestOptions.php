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
        $companyId = $this->get('company_id') ?:
            auth()->user()
                ->owners()
                ->first()
                ->company
                ->id;
        return $this->company = $this->model = $this->company ?:
            Company::findOrFail($companyId);
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