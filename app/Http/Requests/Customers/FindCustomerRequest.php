<?php

namespace App\Http\Requests\Customers;

use Illuminate\Foundation\Http\FormRequest;

use App\Models\Customer;

class FindCustomerRequest extends FormRequest
{
    private $customer;

    public function getCustomer()
    {
        return $this->customer = $this->customer ?: 
            Customer::findOrFail($this->input('id'));
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $user = $this->user();
        $customer = $this->getCustomer();

        return $user->hasCompanyPermission($customer->company_id, 'view customers');
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
