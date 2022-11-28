<?php

namespace App\Http\Requests\Company\Invoices;

use App\Models\Invoice\Invoice;
use App\Traits\CompanyInputRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DeleteInvoiceRequest extends FormRequest
{
    use CompanyInputRequest;

    /**
     * Found invoice from get function execution
     * This variable contains deletion target invoice
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
        $user = $this->user()->fresh();
        return $this->input('force')
            ? $user->can('force-delete-invoice', $invoice)
            : $user->can('delete-invoice', $invoice);
    }

    /**
     * Get invoice from supplied parameters
     * The invoice found can possibly be
     * under the status of soft-deleted status
     *
     * @return Invoice|null
     */
    public function getInvoice(): ?Invoice
    {
        if ($this->invoice) {
            return $this->invoice;
        }
        $id = $this->input('invoice_id');
        $invoice = (new Invoice());
        if (strtobool($this->input('force'))) {
            $invoice = $invoice->onlyTrashed();
        }

        return $this->invoice = $invoice->findOrFail($id);
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

    /**
     * Prepare inputted parameter before validation
     *
     * This function is for deciding if the delete execution is forced or not
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        $force = $this->input('force');
        $this->merge(['force' => strtobool($force)]);
    }
}
