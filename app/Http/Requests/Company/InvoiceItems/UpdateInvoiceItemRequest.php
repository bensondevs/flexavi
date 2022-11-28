<?php

namespace App\Http\Requests\Company\InvoiceItems;

use App\Models\InvoiceItem;
use App\Rules\FloatValue;
use App\Traits\InputRequest;
use Illuminate\Foundation\Http\FormRequest;

class UpdateInvoiceItemRequest extends FormRequest
{
    use InputRequest;

    /**
     * InvoiceItem object
     *
     * @var InvoiceItem|null
     */
    private $invoiceItem;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $item = $this->getInvoiceItem();

        return $this->user()
            ->fresh()
            ->can('update-invoice-item', $item);
    }

    /**
     * Get InvoiceItem based on supplied input
     *
     * @return InvoiceItem
     */
    public function getInvoiceItem()
    {
        if ($this->invoiceItem) {
            return $this->invoiceItem;
        }
        $id = $this->input('id') ?: $this->input('invoice_item_id');

        return $this->invoiceItem = InvoiceItem::findOrFail($id);
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
