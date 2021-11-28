<?php

namespace App\Http\Requests\Invoices;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Models\{ Invoice, Customer };
use App\Traits\CompanyInputRequest;

class SaveInvoiceRequest extends FormRequest
{
    use CompanyInputRequest;

    /**
     * Target customer for invoice creation
     * 
     * @var \App\Models\Customer
     */
    private $customer;

    /**
     * Get customer from the supplied parameter data of `customer_id`
     * 
     * @return \App\Models\Customer|abort 404
     */
    public function getCustomer()
    {
        if ($this->customer) return $this->customer;

        $id = $this->input('customer_id');
        return $this->model = $this->customer = Customer::findOrFail($id);
    }

    /**
     * Prepare input for validation
     * 
     * This will make sure the input of invoice 
     * goes to right company
     * 
     * @return void
     */
    public function prepareForValidation()
    {
        $company = $this->getCompany();
        $this->merge(['company_id' => $company->id]);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $customer = $this->getCustomer();
        return Gate::allows('create-invoice', $customer);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setRules([
            'company_id' => ['required', 'string'],
            'customer_id' => ['required', 'string'],

            'invoice_number' => ['string'],
            'payment_method' => ['numeric'],
        ]);

        return $this->returnRules();
    }
}
