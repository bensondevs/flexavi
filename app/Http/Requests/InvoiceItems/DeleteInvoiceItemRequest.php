<?php

namespace App\Http\Requests\InvoiceItems;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Models\InvoiceItem;

class DeleteInvoiceItemRequest extends FormRequest
{
    private $item;

    public function getInvoiceItem()
    {
        if ($this->item) return $this->item;

        $id = $this->input('id');
        return $this->item = InvoiceItem::findOrFail($id);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $item = $this->getInvoiceItem();
        return Gate::allows('delete-invoice-item', $item);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            
        ];
    }
}
