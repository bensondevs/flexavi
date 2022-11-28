<?php

namespace App\Http\Requests\Company\Customers;

use App\Traits\CompanyInputRequest;
use Illuminate\Foundation\Http\FormRequest;

class PopulateCustomerCityRequest extends FormRequest
{
    use CompanyInputRequest;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()->fresh()->can('view-city-of-customer');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            //
        ];
    }
}
