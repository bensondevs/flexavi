<?php

namespace App\Http\Requests\PaymentTerms;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Traits\InputRequest;

use App\Rules\FloatValue;

use App\Models\PaymentTerm;

class UpdatePaymentTermRequest extends FormRequest
{
    use InputRequest;

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

        $id = $this->input('id');
        return $this->paymentTerm = PaymentTerm::with('invoice')->findOrFail($id);
    }

    protected function prepareForValidation()
    {
        $invoice = $this->getInvoice();
        $this->merge(['company_id' => $invoice->company_id]);
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
            'amount' => ['required', 'numeric', new FloatValue(true), 'min:1', 'max:' . $maximum],
            'due_date' => ['required', 'date'],
        ]);

        return $this->returnRules();
    }

    public function messages()
    {
        $invoice = $this->getInvoice();
        $paymentTerm = $this->getPaymentTerm();
        $totalOutTerms = $invoice->total_out_terms;
        $maximum = $invoice->total_out_terms + $paymentTerm->amount;

        setlocale(LC_MONETARY, 'nl_NL.UTF-8');
        $formattedMaximum = money_format('%(#1n', $maximum);
        return [
            'amount.max' => 'You cannot add payment term amount more than total amount of invoice. Maximum you can add: ' . $formattedMaximum,
        ];
    }
}
