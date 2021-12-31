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

    /**
     * Found invoice model container
     * 
     * @var \App\Models\Invoice
     */
    private $invoice;

    /**
     * Found payment term model container
     * 
     * @var \App\Models\PaymentTerm
     */
    private $paymentTerm;


    /**
     * Get invoice from payment term 
     * relationship of invoice
     * 
     * @return \App\Models\Invoice|null
     */
    public function getInvoice()
    {
        if ($this->invoice) return $this->invoice; 

        $term = $this->getPaymentTerm();
        return $this->invoice = $term->invoice;
    }

    /**
     * Get payment term from supplied input of 
     * `id` or `payment_term_id`
     */
    public function getPaymentTerm()
    {
        if ($this->paymentTerm) return $this->paymentTerm;

        $id = $this->input('id') ?: $this->input('payment_term_id');
        return $this->paymentTerm = PaymentTerm::with('invoice')->findOrFail($id);
    }

    /**
     * Prepare input for validation by 
     * formatting for expected format
     * 
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'company_id' => $this->getCompany()->id,
            'due_date' => carbon()
                ->parse($this->input('due_date'))
                ->toDateString(),
        ]);
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
        $paymentTerm = $this->getPaymentTerm();
        $invoice = $paymentTerm->invoice;
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
