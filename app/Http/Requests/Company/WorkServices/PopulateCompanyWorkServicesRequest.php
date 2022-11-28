<?php

namespace App\Http\Requests\Company\WorkServices;

use App\Traits\CompanyPopulateRequestOptions;
use Illuminate\Foundation\Http\FormRequest;

class PopulateCompanyWorkServicesRequest extends FormRequest
{
    use CompanyPopulateRequestOptions;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()
            ->fresh()
            ->can('view-any-work-service');
    }

    /**
     * Get options
     *
     * @return array
     */
    public function options()
    {
        return $this->collectCompanyOptions();
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
}
