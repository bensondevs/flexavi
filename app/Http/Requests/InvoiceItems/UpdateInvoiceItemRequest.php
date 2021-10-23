<?php

namespace App\Http\Requests\InvoiceItems;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Rules\FloatValue;

use App\Traits\InputRequest;

use App\Models\InvoiceItem;

class UpdateInvoiceItemRequest extends FormRequest
{
    use InputRequest;

    private $invoiceItem;

    public function getInvoiceItem()
    {
        if ($this->invoiceItem) return $this->invoiceItem;

        $id = $this->input('id') ?: $this->input('invoice_item_id');
        return $this->invoiceItem = InvoiceItem::findOrFail($id);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $item = $this->getInvoiceItem();
        return Gate::allows('update-invoice-item', $item);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setRules([
            'item_name' => ['required', 'string'],
            'description' => ['string'],
            'quantity' => ['required', 'integer'],
            'quantity_unit' => ['required', 'string'],
            'amount' => ['required', new FloatValue(true)],
        ]);

        return $this->returnRules();
    }
}
