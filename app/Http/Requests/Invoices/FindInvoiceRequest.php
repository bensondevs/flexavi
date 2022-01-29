<?php

namespace App\Http\Requests\Invoices;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Traits\RequestHasRelations;
use App\Models\Invoice;

class FindInvoiceRequest extends FormRequest
{
    use RequestHasRelations;

    /**
     * List of configirable relationships
     * 
     * @var array
     */
    protected $relationNames = [
        'with_items' => true,
        'with_payment_terms' => true,
        'with_customer' => true,
        'with_invoiceable' => false,
        'with_company' => false,
    ];

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
        return Gate::allows('view-invoice', $invoice);
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
