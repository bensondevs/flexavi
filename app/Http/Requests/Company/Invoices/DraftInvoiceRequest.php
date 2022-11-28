<?php

namespace App\Http\Requests\Company\Invoices;

use App\Enums\Invoice\InvoicePaymentMethod;
use App\Enums\Invoice\InvoiceStatus;
use App\Models\Invoice\Invoice;
use App\Rules\FloatValue;
use App\Traits\CompanyInputRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class DraftInvoiceRequest extends FormRequest
{
    use CompanyInputRequest;

    /**
     * Found invoice from the get function execution
     * container
     *
     * @var Invoice|null
     */
    private ?Invoice $invoice = null;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        $invoice = null;
        if ($this->has('invoice_id')) {
            $invoice = $this->getInvoice();
        }
        return $this->user()->fresh()->can('draft-invoice', $invoice);
    }

    /**
     * Get Invoice based on supplied input
     *
     * @return Invoice|null
     */
    public function getInvoice(): ?Invoice
    {
        if ($this->invoice) {
            return $this->invoice;
        }
        $id = $this->input('invoice_id');

        return $this->invoice = Invoice::findOrFail($id);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function rules(): array
    {
        $company = $this->getCompany();
        return [
            'invoice_id' => ['nullable', 'string', Rule::exists('invoices', 'id')->where('company_id', $company->id)],
            'customer_id' => ['required', 'string', Rule::exists('customers', 'id')->where('company_id', $company->id)],
            'company_id' => ['required', 'string', Rule::exists('companies', 'id')],
            'customer_address' => ['nullable', 'string'],
            'date' => ['nullable', 'date'],
            'due_date' => ['nullable', 'date', 'after_or_equal:date'],
            'payment_method' => ['nullable', 'numeric', Rule::in(InvoicePaymentMethod::getValues())],
            'note' => ['nullable', 'string'],

            'discount_amount' => ['nullable', new FloatValue(true)],
            'potential_amount' => ['nullable', new FloatValue(true)],

            // Invoice items
            'items.*' => ['nullable', 'array'],
            'items.*.work_service_id' => ['nullable', Rule::exists('work_services', 'id')->where('company_id', $company->id)],
            'items.*.amount' => ['nullable', 'numeric'],
        ];
    }


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
            'discount_amount' => floatval($this->input('discount_amount') ?: 0),
            'potential_amount' => floatval($this->input('potential_amount') ?: 0),
        ]);
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
                'discount_amount' => $this->input('discount_amount'),
                'company_id' => $this->getCompany()->id,
                'status' => InvoiceStatus::Drafted,
                'payment_method' => $this->input('payment_method'),
                'note' => $this->input('note'),
            ],
            'invoice_items' => $this->input('items', []),
        ];
    }
}
