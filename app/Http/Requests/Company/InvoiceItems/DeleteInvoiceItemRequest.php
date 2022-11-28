<?php

namespace App\Http\Requests\Company\InvoiceItems;

use App\Models\InvoiceItem;
use Illuminate\Foundation\Http\FormRequest;

class DeleteInvoiceItemRequest extends FormRequest
{
    /**
     * InvoiceItem object
     *
     * @var InvoiceItem|null
     */
    private $item;

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
            ->can('delete-invoice-item', $item);
    }

    /**
     * Get InvoiceItem based on supplied input
     *
     * @return InvoiceItem
     */
    public function getInvoiceItem()
    {
        if ($this->item) {
            return $this->item;
        }
        $id = $this->input('id') ?: $this->input('invoice_item_id');

        return $this->item = InvoiceItem::withTrashed()->findOrFail($id);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [];
    }
}
