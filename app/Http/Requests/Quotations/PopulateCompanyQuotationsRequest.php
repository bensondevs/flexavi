<?php

namespace App\Http\Requests\Quotations;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Models\Company;

use App\Traits\CompanyPopulateRequestOptions;

class PopulateCompanyQuotationsRequest extends FormRequest
{
    use CompanyPopulateRequestOptions;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('view-any-appointment');
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
        $this->setWiths(['customer']);

        return $this->collectCompanyOptions();
    }
}
