<?php

namespace App\Http\Requests\PaymentTerms;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Traits\CompanyInputRequest;

use App\Enums\PaymentTerm\PaymentTermStatus;

use App\Rules\FloatValue;

use App\Models\PaymentTerm;

class UpdatePaymentTermRequest extends FormRequest
{
    use CompanyInputRequest;

    private $invoice;
    private $paymentTerm;

    public function getInvoice()
    {
        if ($this->invoice) return $this->invoice; 

        $term = $this->getPaymentTerm();
        return $this->invoice = $term->invoice;
    }

    public function getPaymentTerm()
    {
        if ($this->paymentTerm) return $this->paymentTerm;

        $id = $this->input('id') ?: $this->input('payment_term_id');
        return $this->paymentTerm = PaymentTerm::with('invoice')->findOrFail($id);
    }

    protected function prepareForValidation()
    {
        $company = $this->getCompany();
        $this->merge(['company_id' => $company->id]);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $paymentTerm = $this->getPaymentTerm();
        return Gate::allows('update-payment-term', $paymentTerm);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $invoice = $this->getInvoice();
        $paymentTerm = $this->getPaymentTerm();
        $totalOutTerms = $invoice->total_out_terms;
        $maximum = $invoice->total_out_terms + $paymentTerm->amount;

        $this->setRules([
            'term_name' => ['required', 'string'],
            'amount' => [
                'required', 
                'numeric', 
                new FloatValue(true), 
                'min:1', 
                'max:' . $maximum
            ],
            'status' => [
                'integer', 
                'min:' . PaymentTermStatus::Unpaid, 
                'max:' . PaymentTermStatus::ForwardedToDebtCollector
            ],
            'due_date' => ['required', 'date'],
        ]);

        return $this->returnRules();
    }
}
