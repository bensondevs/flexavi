<?php

namespace App\Http\Requests\Customers;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Models\Customer;
use App\Traits\RequestHasRelations;

class FindCustomerRequest extends FormRequest
{
    use RequestHasRelations;

    private $relationNames = [
        'with_company' => true,
    ];

    private $customer;

    public function getCustomer()
    {
        if ($this->customer) return $this->customer;

        $id = $this->input('id') ?: $this->input('customer_id');
        return $this->customer = Customer::findOrFail($id);
    }

    protected function prepareForValidation()
    {
        $this->prepareRelationInputs();
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $customer = $this->getCustomer();
        return Gate::allows('view-customer', $customer);
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
