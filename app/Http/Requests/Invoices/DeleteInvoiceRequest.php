<?php

namespace App\Http\Requests\Invoices;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Models\Invoice;

class DeleteInvoiceRequest extends FormRequest
{
    private $invoice;

    public function getInvoice()
    {
        if ($this->invoice) return $this->invoice;

        $id = $this->input('invoice_id') ?: $this->input('id');
        return $this->invoice = Invoice::withTrashed()->findOrFail($id);
    }

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
