<?php

namespace App\Http\Requests\Company\PaymentPickups;

use App\Models\PaymentPickup\PaymentPickup;
use Illuminate\Foundation\Http\FormRequest;

class PickupPaymentRequest extends FormRequest
{
    /**
     * Found payment pickup container
     *
     * @var PaymentPickup|null
     */
    private $paymentPickup;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $paymentPickup = $this->getPaymentPickup();

        return $this->user()
            ->fresh()
            ->can('pickup-payment-pickup', $paymentPickup);
    }

    /**
     * Get PaymentPickup based on supplied input
     */
    public function getPaymentPickup()
    {
        if ($this->paymentPickup) {
            return $this->paymentPickup;
        }
        $id = $this->input('payment_pickup_id') ?: $this->input('id');

        return $this->paymentPickup = PaymentPickup::findOrFail($id);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $paymentPickup = $this->getPaymentPickup();
        $this->setRules([
            'picked_up_amount' => ['required', 'numeric'],
            'picked_up_at' => ['date'],
            'reason_not_all' => [
                'required_unless:picked_up_amount,' .
                $paymentPickup->should_pickup_amount,
                'numeric',
            ],
        ]);

        return $this->returnRules();
    }
}
