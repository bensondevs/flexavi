<?php

namespace App\Http\Requests\Company\PaymentTerms;

use App\Models\PaymentPickup\PaymentTerm;
use Illuminate\Foundation\Http\FormRequest;

class DeletePaymentTermRequest extends FormRequest
{
    /**
     * PaymentTerm object
     *
     * @var PaymentTerm|null
     */
    private $paymentTerm;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $term = $this->getPaymentTerm();

        return $this->user()
            ->fresh()
            ->can('delete-payment-term', $term);
    }

    /**
     * Get PaymentTerm based onsupplied input
     *
     * @return PaymentTerm
     */
    public function getPaymentTerm()
    {
        if ($this->paymentTerm) {
            return $this->paymentTerm;
        }
        $id = $this->input('id') ?: $this->input('payment_term_id');

        return $this->paymentTerm = PaymentTerm::withTrashed()->findOrFail($id);
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
