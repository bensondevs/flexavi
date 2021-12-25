<?php

namespace App\Http\Requests\Customers;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Models\Customer;
use App\Traits\RequestHasRelations;

class FindCustomerRequest extends FormRequest
{
    use RequestHasRelations;

    /**
     * List of configurable relationships
     * 
     * @var array
     */
    protected $relationNames = [
        'with_addresses' => true,
        'with_company' => false,
        'with_quotations' => false,
        'with_appointments' => false,
        'with_invoices' => false,
    ];

    /**
     * Found customer model container
     * 
     * @var \App\Models\Customer|null
     */
    private $customer;

    /**
     * Get customer by supplied input of `id` or `customer_id`
     * 
     * @return \App\Models\Customer
     */
    public function getCustomer()
    {
        if ($this->customer) return $this->customer;

        $id = $this->input('id') ?: $this->input('customer_id');
        return $this->customer = Customer::findOrFail($id);
    }

    /**
     * Prepare input for validation
     * 
     * This will prepare input for configuring relationships
     * 
     * @return void
     */
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
