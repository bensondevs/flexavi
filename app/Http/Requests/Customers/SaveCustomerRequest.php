<?php

namespace App\Http\Requests\Customers;

use Illuminate\Foundation\Http\FormRequest;

class SaveCustomerRequest extends FormRequest
{
    private $customer;

    public function getCustomer()
    {
        return $this->customer = $this->customer ?: 
            Customer::findOrFail(
                request()->input('id')
            );
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->hasCompanyPermission(
            request()->input('company_id')
        );
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'company_id' => ['required', 'string'],
            'fullname' => ['required', 'string', 'alpha'],
            'salutation' => ['required', 'string', 'alpha'],
            'address' => ['required', 'string', 'alpha_num'],
            'house_number' => ['required', 'numeric'],
            'zipcode' => ['required', 'string', 'alpha'],
            'city' => ['required', 'string', 'alpha'],
            'province' => ['required', 'string', 'alpha'],
            'email' => ['required', 'string', 'email', 'unique:customers,email'],
            'phone' => ['required', 'numeric', 'unique:customers,phone'],
        ];

        if (request()->input('second_phone'))
            $rules['second_phone'] = ['required', 'numeric'];

        if (request()->isMethod('PUT') || request()->isMethod('PATCH')) {
            $customer = $this->getCustomer();

            if ($customer->email == request()->input('email'))
                $rules['email'] = ['required', 'email'];

            if ($customer->phone == request()->input('phone'))
                $rules['phone'] = ['required', 'numeric'];
        }

        return $rules;
    }

    public function onlyInRules()
    {
        return $this->only(array_keys($this->rules()));
    }
}
