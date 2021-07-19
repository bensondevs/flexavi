<?php

namespace App\Http\Requests\InvoiceItems;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Rules\FloatValue;

use App\Traits\InputRequest;

use App\Models\Invoice;

class SaveInvoiceItemRequest extends FormRequest
{
    use InputRequest;

    private $invoice;

    public function getInvoice()
    {
        if ($this->invoice) return $this->invoice;

        $id = $this->input('invoice_id');
        return $this->invoice = Invoice::findOrFail($id);
    }

    protected function prepareForValidation()
    {
        $invoice = $this->getInvoice();

        $this->merge([
            'invoice_id' => $invoice->id,
            'company_id' => $invoice->company_id,
        ]);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $invoice = $this->getInvoice();
        return Gate::allows('create-invoice-item', $invoice);
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
}
