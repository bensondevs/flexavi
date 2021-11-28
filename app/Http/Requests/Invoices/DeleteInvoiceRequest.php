<?php

namespace App\Http\Requests\Invoices;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Models\Invoice;

class DeleteInvoiceRequest extends FormRequest
{
    /**
     * Found invoice from get function execution
     * This variable contains deletion target invoice
     * 
     * @var \App\Models\Invoice
     */
    private $invoice;

    /**
     * Get invoice from supplied parameters
     * The invoice found can possibly be 
     * under the status of soft-deleted status
     * 
     * @return \App\Models\Invoice
     */
    public function getInvoice()
    {
        if ($this->invoice) return $this->invoice;

        $id = $this->input('invoice_id') ?: $this->input('id');
        return $this->invoice = Invoice::withTrashed()->findOrFail($id);
    }

    /**
     * Prepare inputted parameter before validation
     * 
     * This function is for deciding if the delete execution is forced or not
     * 
     * @return void
     */
    protected function prepareForValidation()
    {
        $force = $this->input('force');
        $this->merge(['force' => strtobool($force)]);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $invoice = $this->getInvoice();
        return $this->input('force') ?
            Gate::allows('force-delete-invoice', $invoice) :
            Gate::allows('delete-invoice', $invoice);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }
}
