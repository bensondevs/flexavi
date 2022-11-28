<?php

namespace App\Http\Requests\Company\Invoices;

use App\Models\Invoice\Invoice;
use Illuminate\Foundation\Http\FormRequest;

class PrintInvoiceRequest extends FormRequest
{
    /**
     * Found invoice from get function execution
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
        $invoice = $this->getInvoice();

        return $this->user()
            ->fresh()
            ->can('print-invoice', $invoice);
    }

    /**
     * Get invoice from supplied parameter of `id` or `invoice_id`
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
     * @return array
     */
    public function rules(): array
    {
        return [];
    }
}
