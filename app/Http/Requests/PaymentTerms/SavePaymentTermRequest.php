<?php

namespace App\Http\Requests\PaymentTerms;

use Illuminate\Foundation\Http\FormRequest;

use App\Traits\InputRequest;

use App\Models\FloatValue;

use App\Models\Invoice;
use App\Models\PaymentTerm;

class SavePaymentTermRequest extends FormRequest
{
    use InputRequest;

    private $invoice;
    private $paymentTerm;

    public function getInvoice()
    {
        return $this->invoice = $this->invoice ?:
            Invoice::findOrFail($this->input('invoice_id'));
    }

    public function getPaymentTerm()
    {
        return $this->paymentTerm = $this->paymentTerm ?:
            PaymentTerm::findOrFail($this->input('id'));
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

        // Add Company ID as input
        $this->merge(['company_id' => $invoice->company_id]);

        $actionName = ($this->isMethod('POST')) ? 'create' : 'edit';
        $actionObject = 'payment terms';
        $action = $actionName . ' ' . $actionObject;
        return $user->hasCompanyPermission(
            $invoice->company_id, 
            $action
        );
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setRules([
            'invoice_id' => ['required', 'string'],
            'term_name' => ['required', 'string'],
            'amount' => ['required', new FloatValue(true)],
            'due_date' => ['required', 'datetime'],
        ]);

        return $this->returnRules();
    }
}
