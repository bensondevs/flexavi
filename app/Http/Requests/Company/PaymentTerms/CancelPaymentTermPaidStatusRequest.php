<?php

namespace App\Http\Requests\Company\PaymentTerms;

use App\Models\PaymentPickup\PaymentTerm;
use App\Traits\InputRequest;
use Illuminate\Foundation\Http\FormRequest;

class CancelPaymentTermPaidStatusRequest extends FormRequest
{
    use InputRequest;

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
            ->can('update-payment-term', $term);
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

        return $this->paymentTerm = PaymentTerm::findOrFail($id);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setRules([
            'reason' => ['required', 'string'],
        ]);

        return $this->returnRules();
    }
}
