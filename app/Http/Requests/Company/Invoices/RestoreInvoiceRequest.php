<?php

namespace App\Http\Requests\Company\Invoices;

use App\Models\Invoice\Invoice;
use App\Traits\CompanyInputRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RestoreInvoiceRequest extends FormRequest
{
    use CompanyInputRequest;

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
            ->can('restore-invoice', $invoice);
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

        return $this->invoice = Invoice::onlyTrashed()->findOrFail($id);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'invoice_id' => [
                'required',
                'string',
                Rule::exists('invoices', 'id')->where(function ($query) {
                    $query->where('company_id', $this->getCompany()->id);
                })
            ],
        ];
    }
}
