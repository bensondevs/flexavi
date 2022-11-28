<?php

namespace App\Http\Requests\Company\Customers;

use App\Models\Customer\Customer;
use Illuminate\Foundation\Http\FormRequest;

class DeleteCustomerRequest extends FormRequest
{
    /**
     * Customer object
     *
     * @var Customer|null
     */
    private $customer;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $customer = $this->getCustomer();

        return $this->user()
            ->fresh()
            ->can('delete-customer', $customer);
    }

    /**
     * Get Customer based on supplied input
     *
     * To prevent repeated query, the result of query will be stored
     * inside the $customer attribute above and get data from that variable
     * once the data is stored into variable
     *
     * @return Customer
     */
    public function getCustomer()
    {
        if ($this->customer) {
            return $this->customer;
        }
        $id = $this->input('id') ?: $this->input('customer_id');

        return $this->customer = Customer::withTrashed()->findOrFail($id);
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
}
