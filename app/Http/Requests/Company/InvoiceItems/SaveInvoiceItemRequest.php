<?php

namespace App\Http\Requests\Company\InvoiceItems;

use App\Models\Invoice\Invoice;
use App\Rules\FloatValue;
use App\Traits\InputRequest;
use Illuminate\Foundation\Http\FormRequest;

class SaveInvoiceItemRequest extends FormRequest
{
    use InputRequest;

    /**
     * Invoice object
     *
     * @var Invoice|null
     */
    private $invoice;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $invoice = $this->getInvoice();

        return $this->user()
            ->fresh()
            ->can('create-invoice-item', $invoice);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setRules([
            'company_id' => ['required', 'string'],
            'invoice_id' => ['required', 'string'],
            'item_name' => ['required', 'string'],
            'description' => ['string'],
            'quantity' => ['required', 'integer'],
            'quantity_unit' => ['required', 'string'],
            'amount' => ['required', new FloatValue(true)],
        ]);

        return $this->returnRules();
    }

    /**
     * Prepare for validation
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $invoice = $this->getInvoice();
        $this->merge([
            'invoice_id' => $invoice->id,
            'company_id' => $invoice->company_id,
        ]);
    }

    /**
     * Get Invoice based on supplied input
     *
     * @return Invoice
     */
    public function getInvoice()
    {
        if ($this->invoice) {
            return $this->invoice;
        }
        $id = $this->input('invoice_id');

        return $this->invoice = Invoice::findOrFail($id);
    }
}
