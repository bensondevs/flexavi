<?php

namespace App\Http\Requests\WorkConditionPhotos;

use Illuminate\Foundation\Http\FormRequest;

use App\Traits\CompanyPopulateRequestOptions;

class PopulateCompanyWorkConditionPhotosRequest extends FormRequest
{
    use CompanyPopulateRequestOptions;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
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
