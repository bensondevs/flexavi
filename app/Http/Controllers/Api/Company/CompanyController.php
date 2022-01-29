<?php

namespace App\Http\Controllers\Api\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Companies\{
    SaveCompanyRequest as SaveRequest,
    PopulateCompanyOwnersRequest as PopulateOwnersRequest,
    RegisterCompanyRequest as RegisterRequest,
    UploadCompanyLogoRequest as UploadLogoRequest
};

use App\Http\Resources\{
    CompanyResource,
    SettingResource,
    Users\UserCompanyResource
};

use App\Models\Setting;

use App\Repositories\{
    CompanyRepository,
    CompanyOwnerRepository
};

class CompanyController extends Controller
{
    /**
     * Company Repository Class Container
     * 
     * @var \App\Repositories\CompanyRepository
     */
    protected $company;

    /**
     * Owner Repository Class Container
     * 
     * @var \App\Repositories\CompanyOwnerRepository
     */
    protected $owner;

    /**
     * Controller Constructor Method
     * 
     * @param \App\Repository\CompanyRepository  $company
     * @param \App\Repository\CompanyOwnerRepository  $owner
     * @return void 
     */
    public function __construct(
        CompanyRepository $company, 
        CompanyOwnerRepository $owner
    ) {
    	$this->company = $company;
        $this->owner = $owner;
    }

    /**
     * Company of the owner user
     * 
     * @return Illuminate\Support\Facades\Response
     */
    public function userCompany()
    {
        if (! $owner = (auth()->user())->owner) {
            return abort(404, 'This user is not owner of company.');
        }
        
        $company = new CompanyResource($owner->company);
        return response()->json(['company' => $company]);
    }

    /**
     * Upload Company Logo
     * 
     * @param UploadLogoRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function uploadCompanyLogo(UploadLogoRequest $request)
    {
        $company = $request->getCompany();
        $company = $this->company->setModel($company);
        
        $logo = $request->company_logo;
        $company = $this->company->uploadCompanyLogo($logo);

        return apiResponse($this->company);
    }

    /**
     * Register company
     * 
     * @param RegisterRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function register(RegisterRequest $request)
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

    /**
     * Upload company
     * 
     * @param  SaveRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function update(SaveRequest $request)
    {
        $company = $request->getCompany();
        $company = $this->company->setModel($company);

        $input = $request->companyData();
        $company = $this->company->save($input);

        return apiResponse($this->company);
    }

    /**
     * Delete company
     * 
     * @param  DeleteRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function delete(DeleteRequest $request)
    {
        $company = $request->getCompany();
        
        $this->company->setModel($company);
        $this->company->delete($company, false);

        return apiResponse($this->company);
    }
}
