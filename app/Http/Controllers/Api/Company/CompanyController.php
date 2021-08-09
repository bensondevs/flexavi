<?php

namespace App\Http\Controllers\Api\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Resources\Users\UserCompanyResource;

use App\Http\Requests\Companies\SaveCompanyRequest as SaveRequest;
use App\Http\Requests\Companies\PopulateCompanyOwnersRequest as PopulateOwnersRequest;
use App\Http\Requests\Companies\RegisterCompanyRequest as RegisterRequest;
use App\Http\Requests\Companies\UploadCompanyLogoRequest as UploadLogoRequest;

use App\Http\Resources\CompanyResource;

use App\Repositories\CompanyRepository;
use App\Repositories\CompanyOwnerRepository;

class CompanyController extends Controller
{
    protected $company;
    protected $owner;

    public function __construct(CompanyRepository $company, CompanyOwnerRepository $owner)
    {
    	$this->company = $company;
        $this->owner = $owner;
    }

    public function userCompany()
    {
        if (! $owner = (auth()->user())->owner) {
            return abort(404, 'This user is not owner of company.');
        }
        
        $company = new CompanyResource($owner->company);
        return response()->json(['company' => $company]);
    }

    public function uploadCompanyLogo(UploadLogoRequest $request)
    {
        $company = $request->getCompany();
        $company = $this->company->setModel($company);
        
        $logo = $request->company_logo;
        $company = $this->company->uploadCompanyLogo($logo);

        return apiResponse($this->company);
    }

    public function register(SaveRequest $request)
    {
        // Create Company
        $input = $request->companyData();
        $company = $this->company->save($input);

        // Assign Company to Owner
        $owner = $request->user()->owner;
        $owner = $this->owner->setModel($owner);
        $owner = $this->owner->assignCompany($company);

        return apiResponse($this->company);
    }

    public function update(SaveRequest $request)
    {
        $company = $request->getCompany();
        $company = $this->company->setModel($company);

        $input = $request->companyData();
        $company = $this->company->save($input);

        return apiResponse($this->company);
    }
}
