<?php

namespace App\Http\Requests\Company\InvoiceLogs;

use App\Models\Invoice\Invoice;
use App\Traits\PopulateRequestOptions;
use Illuminate\Foundation\Http\FormRequest;

class PopulateInvoiceLogsRequest extends FormRequest
{
    use PopulateRequestOptions;

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
        return true;
    }

    /**
     * Populate options of request
     *
     * @return array
     */
    public function options(): array
    {
        $this->addOrderBy('created_at', 'desc');

        $this->addWhere([
            'column' => 'invoice_id',
            'value' => $this->getInvoice()->id
        ]);

        return $this->collectOptions();
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
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'invoice_id' => ['required', 'string', 'exists:invoices,id']
        ];
    }
}
