<?php

namespace App\Http\Requests\Owners;

use Illuminate\Foundation\Http\FormRequest;

use App\Traits\CompanyPopulateRequestOptions;

class PopulateCompanyOwnersRequest extends FormRequest
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
        $company = $this->model = $this->getCompany();

        return $this->authorizeCompanyAction('companies', 'id');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'page' => ['integer', 'min:1'],
            'search' => ['string'],
        ];
    }

    public function options()
    {
        $this->setWiths(['user']);

        return $this->collectCompanyOptions();
    }
}