<?php

namespace App\Http\Requests\Invoices;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Models\Invoice;
use App\Traits\InputRequest;

class UpdateInvoiceRequest extends FormRequest
{
    use InputRequest;

    /**
     * Found invoice from the get function execution
     * container
     * 
     * @var \App\Models\Invoice
     */
    private $invoice;

    /**
     * Get invoice from supplied parameter of
     * `id` of `invoice_id`
     * 
     * @return \App\Models\Invoice
     */
    public function getInvoice()
    {
        if ($this->invoice) return $this->invoice;

        $id = $this->input('id') ?: $this->input('invoice_id');
        return $this->invoice = Invoice::findOrFail($id);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $invoice = $this->getInvoice();
        return Gate::allows('update-invoice', $invoice);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setRules([
            'payment_method' => ['required', 'integer', 'min:1', 'max:2'],
        ]);

        return $this->returnRules();
    }
}
