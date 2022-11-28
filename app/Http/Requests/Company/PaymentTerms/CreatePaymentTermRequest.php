<?php

namespace App\Http\Requests\Company\PaymentTerms;

use App\Enums\PaymentTerm\PaymentTermStatus;
use App\Models\Invoice\Invoice;
use App\Rules\FloatValue;
use App\Traits\InputRequest;
use Illuminate\Foundation\Http\FormRequest;

class CreatePaymentTermRequest extends FormRequest
{
    use InputRequest;

    /**
     * Target invoice to create payment term
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
            ->can('create-payment-term', $invoice);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $invoice = $this->getInvoice();
        $this->setRules([
            'invoice_id' => ['required', 'string'],
            'term_name' => ['required', 'string'],
            'amount' => [
                'required',
                'numeric',
                new FloatValue(true),
                'min:1',
                'max:' . $invoice->total_out_terms,
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
     * Get validation messages
     *
     * @return array
     */
    public function messages()
    {
        $invoice = $this->getInvoice();

        return [
            'amount.max' =>
                'You cannot add payment term amount more than total amount of invoice. Maximum you can add: ' .
                $invoice->formatted_total_out_terms,
        ];
    }

    /**
     * Prepare for validation
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $invoice = $this->getInvoice();
        $this->merge(['company_id' => $invoice->company_id]);
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
}
