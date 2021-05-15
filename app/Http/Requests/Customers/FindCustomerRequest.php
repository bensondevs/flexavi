<?php

namespace App\Http\Requests\Customers;

use Illuminate\Foundation\Http\FormRequest;

use App\Models\Customer;

class FindCustomerRequest extends FormRequest
{
    private $customer;

    public function getCustomer()
    {
        return $this->customer ?: 
            Customer::findOrFail(request()->input('id'));
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->hasCompanyPermission(
            $this->getCustomer()->company_id
        );
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            
        ];
    }
}
