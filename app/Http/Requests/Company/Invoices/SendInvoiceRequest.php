<?php

namespace App\Http\Requests\Company\Invoices;

use App\Enums\Invoice\InvoicePaymentMethod;
use App\Enums\Invoice\InvoiceStatus;
use App\Models\Invoice\Invoice;
use App\Rules\FloatValue;
use App\Traits\CompanyInputRequest;
use App\Traits\InputRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class SendInvoiceRequest extends FormRequest
{
    use InputRequest, CompanyInputRequest;

    /**
     * Found invoice from get function execution
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
        if (!$this->has('invoice_id')) {
            return $this->user()->fresh()->can('create-invoice');
        }

        $invoice = $this->getInvoice();
        if ($invoice->isDrafted()) {
            return $this->user()->fresh()->can('send-invoice', $invoice);
        }

        if ($invoice->status >= InvoiceStatus::Sent) {
            return $this->user()->fresh()->can('resend-invoice', $invoice);
        }

        return false;
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
            'customer_address' => ['required', 'string'],
            'date' => ['required', 'date'],
            'due_date' => ['required', 'date', 'after_or_equal:date'],
            'payment_method' => ['required', 'numeric', Rule::in(InvoicePaymentMethod::getValues())],
            'note' => ['nullable', 'string'],

            'discount_amount' => ['nullable', new FloatValue(true)],
            'potential_amount' => ['nullable', new FloatValue(true)],

            // Invoice items
            'items.*' => ['required', 'array'],
            'items.*.work_service_id' => ['required', Rule::exists('work_services', 'id')->where('company_id', $company->id)],
            'items.*.amount' => ['required', 'numeric'],
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
                'status' => InvoiceStatus::Sent,
                'payment_method' => $this->input('payment_method'),
                'note' => $this->input('note'),
                'sent_at' => now(),
            ],
            'invoice_items' => $this->input('items', []),
        ];
    }
}
