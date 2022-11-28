<?php

namespace App\Http\Requests\Company\Invoices;

use App\Enums\Invoice\InvoicePaymentMethod;
use App\Enums\Invoice\InvoiceStatus;
use App\Enums\Quotation\QuotationStatus;
use App\Models\Customer\Customer;
use App\Rules\FloatValue;
use App\Rules\Helpers\Media;
use App\Traits\CompanyInputRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class SaveInvoiceRequest extends FormRequest
{
    use CompanyInputRequest;

    /**
     * Found customer from the get function execution
     * container
     *
     * @var Customer|null
     */
    private ?Customer $customer = null;


    /**
     * Prepare input for validation
     *
     * This will make sure the input of invoice
     * goes to right company
     *
     * @return void
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function prepareForValidation(): void
    {
        $company = $this->getCompany();
        $this->merge([
            'company_id' => $company->id,
            'vat_percentage' => floatval($this->input('vat_percentage') ?: 0),
            'discount_amount' => floatval($this->input('discount_amount') ?: 0),
            'potential_amount' => floatval($this->input('potential_amount') ?: 0),
        ]);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        $customer = $this->getCustomer();

        return $this->user()
            ->fresh()
            ->can('create-invoice', $customer);
    }

    /**
     * Get customer from the supplied parameter data of `customer_id`
     *
     * @return ?Customer
     */
    public function getCustomer(): ?Customer
    {
        if ($this->customer) {
            return $this->customer;
        }
        $id = $this->input('customer_id');

        return $this->customer = Customer::findOrFail($id);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function rules(): array
    {
        $company = $this->getCompany();
        return [
            'customer_id' => ['required', 'string', Rule::exists('customers', 'id')->where('company_id', $company->id)],
            'customer_address' => ['required', 'string'],
            'date' => ['required', 'date'],
            'due_date' => ['required', 'date', 'after_or_equal:date'],
            'payment_method' => ['required', 'numeric', Rule::in(InvoicePaymentMethod::getValues())],
            'note' => ['nullable', 'string'],
            'status' => ['required', Rule::in([InvoiceStatus::Drafted, QuotationStatus::Sent])],

            'tax_percentage' => ['nullable', new FloatValue(true)],
            'discount_amount' => ['nullable', new FloatValue(true)],
            'potential_amount' => ['nullable', new FloatValue(true)],

            'signature' => ['nullable', 'image', 'max:' . Media::MAX_IMAGE_SIZE, 'mimes:' . Media::imageExtensions()],

            // Invoice items
            'items.*' => ['required', 'array'],
            'items.*.work_service_id' => ['required', Rule::exists('work_services', 'id')->where('company_id', $company->id)],
            'items.*.amount' => ['required', 'numeric'],
        ];
    }

    /**
     * Get invoice data from the request
     *
     * @return array
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function invoiceData(): array
    {
        return [
            'invoice_data' => [
                'date' => $this->input('date'),
                'due_date' => $this->input('due_date'),
                'customer_id' => $this->input('customer_id'),
                'customer_address' => $this->input('customer_address'),
                'potential_amount' => $this->input('potential_amount'),
                'vat_percentage' => $this->input('vat_percentage'),
                'discount_amount' => $this->input('discount_amount'),
                'company_id' => $this->getCompany()->id,
                'status' => $this->input('status'),
                'signature' => $this->file('signature'),
                'note' => $this->input('note'),
            ],
            'invoice_items' => $this->input('items'),
        ];
    }

}
