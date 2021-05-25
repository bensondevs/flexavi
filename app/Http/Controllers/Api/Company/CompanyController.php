<?php

namespace App\Http\Controllers\Api\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Resources\Users\UserCompanyResource;

use App\Http\Requests\Companies\PopulateCompaniesRequest;
use App\Http\Requests\Companies\SaveCompanyRequest;
use App\Http\Requests\Companies\RegisterCompanyRequest;

use App\Repositories\CompanyRepository;
use App\Repositories\CompanyOwnerRepository;

class CompanyController extends Controller
{
    protected $company;
    protected $owner;

    public function __construct(
        CompanyRepository $company,
        CompanyOwnerRepository $owner
    )
    {
    	$this->company = $company;
        $this->owner = $owner;
    }

    public function userCompany()
    {
        $user = auth()->user();
        $company = $user->owner->company;

        return response()->json(['company' => $company]);
    }

    public function registerCompany(RegisterCompanyRequest $request)
    {
        $input = $request->registerData();
        $company = $this->company->register($input);

        return apiResponse($this->company, $company);
    }

    public function store(StoreCompanyRequest $request)
    {
        $input = $request->onlyInRules();
        $company = $this->company->save($input);

        return apiResponse($this->company, $company);
    }

    public function update(SaveCompanyRequest $request)
    {
        $input = $request->onlyInRules();
        $this->company->setModel($request->getCompany());
        $this->company->save($input);

        return apiResponse(
            $this->company, 
            $this->company->getModel()
        );
    }
}
