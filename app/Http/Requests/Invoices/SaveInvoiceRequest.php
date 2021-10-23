<?php

namespace App\Http\Requests\Invoices;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Traits\CompanyInputRequest;

use App\Models\{ Invoice, Customer };

class SaveInvoiceRequest extends FormRequest
{
    use CompanyInputRequest;

    private $customer;

    public function getCustomer()
    {
        if ($this->customer) return $this->customer;

        $id = $this->input('customer_id');
        $this->customer = Customer::findOrFail($id);
    }

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
