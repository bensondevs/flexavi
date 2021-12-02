<?php

namespace App\Http\Requests\Customers;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Models\Customer;

class DeleteCustomerRequest extends FormRequest
{
    /**
     * Found customer data container after the getCustomer() 
     * function firstly called.
     * 
     * @var \App\Models\Customer|null
     */
    private $customer;

    /**
     * Get target customer from supplied input of
     * `id` or `customer_id`. 
     * 
     * To prevent repeated query, the result of query will be stored
     * inside the $customer attribute above and get data from that variable
     * once the data is stored into variable.
     * 
     * @return \App\Models\Customer|abort 404
     */
    public function getCustomer()
    {
        if ($this->customer) return $this->customer;

        $id = $this->input('id') ?: $this->input('customer_id');
        return $this->customer = Customer::withTrashed()->findOrFail($id);
    }

    /**
     * Prepare needed value for validation and input
     * 
     * @return void
     */
    protected function prepareForValidation()
    {
        if ($force = $this->input('force')) {
            $this->merge(['force' => strtobool($force)]);
        }
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $customer = $this->getCustomer();
        return Gate::allows('delete-customer', $customer);
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
}
