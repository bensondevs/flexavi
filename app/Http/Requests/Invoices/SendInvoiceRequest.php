<?php

namespace App\Http\Requests\Invoices;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Models\Invoice;
use App\Traits\InputRequest;

class SendInvoiceRequest extends FormRequest
{
    use InputRequest;

    /**
     * Found invoice from get function execution
     * container
     * 
     * @var \App\Models\Invoice
     */
    private $invoice;

    /**
     * Get invoice from supplied parameter of
     * `invoice_id` or `id`
     * 
     * @return \App\Models\Invoice|abort 404
     */
    public function getInvoice()
    {
        if ($this->invoice) return $this->invoice;

        $id = $this->input('invoice_id') ?: $this->input('id');
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
        return Gate::allows('send-invoice', $invoice);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setRules([
            'destination_email' => ['required', 'email'],
        ]);

        return $this->returnRules();
    }
}
