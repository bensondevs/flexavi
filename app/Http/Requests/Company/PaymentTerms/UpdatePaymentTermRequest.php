<?php

namespace App\Http\Requests\Company\PaymentTerms;

use App\Enums\PaymentTerm\PaymentTermStatus;
use App\Models\{Invoice\Invoice, PaymentPickup\PaymentTerm};
use App\Rules\FloatValue;
use App\Traits\CompanyInputRequest;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePaymentTermRequest extends FormRequest
{
    use CompanyInputRequest;

    /**
     * Found invoice model container
     *
     * @var Invoice|null
     */
    private $invoice;

    /**
     * Found payment term model container
     *
     * @var PaymentTerm|null
     */
    private $paymentTerm;

    /**
     * Get invoice from payment term relationship of invoice
     *
     * @return Invoice|null
     */
    public function getInvoice()
    {
        if ($this->invoice) {
            return $this->invoice;
        }
        $term = $this->getPaymentTerm();

        return $this->invoice = $term->invoice;
    }

    /**
     * Get PaymentTerm based on supplied input
     *
     * @return PaymentTerm
     */
    public function getPaymentTerm()
    {
        if ($this->paymentTerm) {
            return $this->paymentTerm;
        }
        $id = $this->input('id') ?: $this->input('payment_term_id');

        return $this->paymentTerm = PaymentTerm::with('invoice')->findOrFail(
            $id
        );
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $paymentTerm = $this->getPaymentTerm();

        return $this->user()
            ->fresh()
            ->can('update-payment-term', $paymentTerm);
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
        $maximum = $invoice->total_out_terms + $paymentTerm->amount;
        $this->setRules([
            'term_name' => ['required', 'string'],
            'amount' => [
                'required',
                'numeric',
                new FloatValue(true),
                'min:1',
                'max:' . $maximum,
            ],
            'status' => [
                'integer',
                'min:' . PaymentTermStatus::Unpaid,
                'max:' . PaymentTermStatus::ForwardedToDebtCollector,
            ],
            'due_date' => ['required', 'date'],
        ]);

        return $this->returnRules();
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
}
