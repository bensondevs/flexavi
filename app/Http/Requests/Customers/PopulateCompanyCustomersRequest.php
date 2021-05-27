<?php

namespace App\Http\Requests\Customers;

use Illuminate\Foundation\Http\FormRequest;

use App\Traits\CompanyPopulateRequestOptions;

class PopulateCompanyCustomersRequest extends FormRequest
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

        return $user->hasCompanyPermission($company->id, 'view customers');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }

    public function options()
    {
        return $this->collectCompanyOptions();
    }
}
