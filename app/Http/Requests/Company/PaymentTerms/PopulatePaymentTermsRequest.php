<?php

namespace App\Http\Requests\Company\PaymentTerms;

use App\Models\Invoice\Invoice;
use App\Traits\PopulateRequestOptions;
use Illuminate\Foundation\Http\FormRequest;

class PopulatePaymentTermsRequest extends FormRequest
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
            ->can('view-any-payment-term', $invoice);
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
        $id = $this->input('id') ?: $this->input('invoice_id');

        return $this->invoice = Invoice::findOrFail($id);
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
        if ($this->has('status')) {
            $this->addWhere([
                'column' => 'status',
                'value' => $this->input('status'),
            ]);
        }

        return $this->collectOptions();
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
