<?php

namespace App\Http\Requests\Auths\Customer;

use Illuminate\Foundation\Http\FormRequest;

use App\Models\Customer;

class CustomerResetUniqueKeyRequest extends FormRequest
{
    private $customer;

    public function getCustomer()
    {
        if ($this->customer) return $this->customer;

        return $this->customer = Customer::findUsingCredentials([
            'zipcode' => $this->input('zipcode'),
            'house_number' => $this->input('house_number'),
        ]);
    }

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
            'zipcode' => ['required', 'numeric'],
            'house_number' => ['required', 'numeric'],
        ];
    }
}
