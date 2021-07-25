<?php

namespace App\Http\Requests\Customers;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Models\Customer;

use App\Rules\ZipCode;
use App\Rules\NotEqual;
use App\Rules\AmongStrings;
use App\Rules\CompanyOwned;

use App\Traits\CompanyInputRequest;

class SaveCustomerRequest extends FormRequest
{
    use CompanyInputRequest;

    private $customer;

    public function getCustomer()
    {
        if ($this->customer) return $this->customer;

        $id = $this->input('id') ?: $this->input('customer_id');
        return $this->customer = $this->model = Customer::findOrFail($id);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if ($this->isMethod('POST')) {
            return Gate::allows('create-customer');
        }
    
        $customer = $this->getCustomer();
        return Gate::allows('edit-customer', $customer);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setRules([
            'fullname' => ['required', 'string', 'regex:/^[\pL\s\-]+$/u'],
            'email' => ['string', 'email', 'unique:customers,email'],
            'phone' => ['required', 'numeric', 'unique:customers,phone'],
            'second_phone' => ['numeric', new NotEqual('phone')],
        ]);

        return $this->returnRules();
    }
}
