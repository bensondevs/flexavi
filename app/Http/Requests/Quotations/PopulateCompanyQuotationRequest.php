<?php

namespace App\Http\Requests\Quotations;

use Illuminate\Foundation\Http\FormRequest;

use App\Models\Company;

use App\Traits\CompanyPopulateRequestOptions;

class PopulateCompanyQuotationRequest extends FormRequest
{
    use CompanyPopulateRequestOptions;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $user = $this->user();
        $company = $this->getCompany();

        return $user->hasCompanyPermission($company->id);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [];
    }

    public function options()
    {
        $this->setWiths(['creator', 'customer', 'photos']);

        return $this->collectCompanyOptions();
    }
}
