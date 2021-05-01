<?php

namespace App\Http\Controllers\Api\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Resources\Users\UserCompanyResource;

use App\Http\Requests\Companies\StoreCompanyRequest;
use App\Http\Requests\Companies\UpdateCompanyRequest;

use App\Repositories\CompanyRepository;

class CompanyController extends Controller
{
    protected $company;

    public function __construct(CompanyRepository $company)
    {
    	$this->company = $company;
    }

    public function userCompanies()
    {
        $companies = auth()->user()->companies;

        return response()->json([
            'companies' => UserCompanyResource::collection($companies)
        ]);
    }

    public function registerCompany(RegisterCompanyRequest $request)
    {
        $input = $request->onlyInRules();
        
        $this->company->setModel($request->getCompany());
        $this->company->save($request->onlyInRules());

        return apiResponse($this->company, $this->company->getModel());
    }

    public function store(StoreCompanyRequest $request)
    {S
        $input = $request->onlyInRules();
        $company = $this->company->save($input);

        return apiResponse($this->company, $company);
    }

    public function update(SaveCompanyRequest $request)
    {
        $input = $request->onlyInRules();
        $this->company->find($request->input('id'));
        $this->company->save($input);

        return apiResponse(
            $this->company, 
            $this->company->getModel()
        );
    }
}
