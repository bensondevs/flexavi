<?php

namespace App\Http\Requests\InvoiceItems;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Traits\PopulateRequestOptions;

use App\Models\Invoice;

class PopulateInvoiceItemsRequest extends FormRequest
{
    use PopulateRequestOptions;

    private $invoice;

    public function getInvoice()
    {
        if ($this->invoice) return $this->invoice;

        $id = $this->input('invoice_id');
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
        return Gate::allows('view-any-invoice-item', $invoice);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'invoice_id' => ['required', 'string'],
        ];
    }

    public function options()
    {
        $this->addWhere([
            'column' => 'invoice_id',
            'value' => $this->getInvoice()->id,
        ]);

        return $this->collectOptions();
    }
}
