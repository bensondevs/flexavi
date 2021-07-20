<?php

namespace App\Http\Requests\PaymentTerms;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Traits\PopulateRequestOptions;

use App\Models\Invoice;

class PopulatePaymentTermsRequest extends FormRequest
{
    use PopulateRequestOptions;

    private $invoice;

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
        return Gate::allows('view-any-payment-term', $invoice);
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

    public function options()
    {
        $this->addWhere([
            'column' => 'invoice_id',
            'value' => $this->getInvoice()->id,
        ]);

        return $this->collectOptions();
    }
}
