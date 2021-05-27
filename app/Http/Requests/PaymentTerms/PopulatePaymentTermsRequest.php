<?php

namespace App\Http\Requests\PaymentTerms;

use Illuminate\Foundation\Http\FormRequest;

use App\Traits\PopulateRequestOptions;

use App\Models\Invoice;

class PopulatePaymentTermsRequest extends FormRequest
{
    use PopulatePaymentTermsRequest;

    private $invoice;

    public function getInvoice()
    {
        return $this->invoice = ($this->invoice) ?:
            Invoice::findOrFail($this->input('id'));
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $user = $this->user();
        $invoice = $this->getInvoice();

        return $user->hasCompanyPermission(
            $invoice->company_id, 
            'view payment terms'
        );
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
