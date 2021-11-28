<?php

namespace App\Http\Requests\Invoices;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Traits\InputRequest;
use App\Models\Invoice;
use App\Enums\Invoice\InvoiceStatus;

class ChangeInvoiceStatusRequest extends FormRequest
{
    use InputRequest;

    /**
     * Found invoice from get function execution
     * 
     * @var \App\Models\Invoice
     */
    private $invoice;

    /**
     * Get invoice from supplied parameter of `id` or `invoice_id`
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
        return Gate::allows('change-status-invoice', $invoice);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setRules([
            'status' => [
                'required', 
                'numeric', 
                'min:' . InvoiceStatus::Created,
                'max:' . InvoiceStatus::PaidViaDebtCollector,
            ],
        ]);

        return $this->returnRules();
    }
}
