<?php

namespace App\Http\Requests\Company\InvoiceItems;

use App\Models\Invoice\Invoice;
use App\Traits\PopulateRequestOptions;
use Illuminate\Foundation\Http\FormRequest;

class PopulateInvoiceItemsRequest extends FormRequest
{
    use PopulateRequestOptions;

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
            ->can('view-any-invoice-item', $invoice);
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

    /**
     * Get options
     *
     * @return array
     */
    public function options()
    {
        $this->addWhere([
            'column' => 'invoice_id',
            'value' => $this->getInvoice()->id,
        ]);

        return $this->collectOptions();
    }
}
