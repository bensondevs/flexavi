<?php

namespace App\Http\Requests\Company\Customers;

use App\Models\Customer\Customer;
use App\Traits\RequestHasRelations;
use Illuminate\Foundation\Http\FormRequest;

class FindCustomerRequest extends FormRequest
{
    use RequestHasRelations;

    /**
     * List of configurable relationships
     *
     * @var array
     */
    protected $relationNames = [
        'with_address' => false,
        'with_addresses' => false,
        'with_company' => false,
        'with_quotations' => false,
        'with_appointments' => false,
        'with_invoices' => false,
        'with_notes' => false,
        'with_workContracts' => false,
        'with_workContracts.contents' => false,
    ];

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
            ->can('view-customer', $customer);
    }

    /**
     * Get Customer based on supplied input
     *
     * @return Customer
     */
    public function getCustomer()
    {
        if ($this->customer) {
            return $this->customer;
        }
        $id = $this->input('id') ?: $this->input('customer_id');

        return $this->customer = Customer::with($this->getRelations())->findOrFail($id);
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
}
