<?php

namespace App\Http\Requests\Company\SubscriptionTest;

use App\Traits\CompanyPopulateRequestOptions;
use Illuminate\Foundation\Http\FormRequest;

class SaveRequest extends FormRequest
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
        return [];
    }
}
