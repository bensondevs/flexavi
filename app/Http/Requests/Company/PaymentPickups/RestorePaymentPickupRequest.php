<?php

namespace App\Http\Requests\Company\PaymentPickups;

use App\Models\PaymentPickup\PaymentPickup;
use Illuminate\Foundation\Http\FormRequest;

class RestorePaymentPickupRequest extends FormRequest
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
            ->can('restore-payment-pickup', $paymentPickup);
    }

    /**
     * Get PaymentPickup based on supplied input
     *
     * @return PaymentPickup
     */
    public function getPaymentPickup()
    {
        if ($this->paymentPickup) {
            return $this->paymentPickup;
        }
        $id = $this->input('payment_pickup_id') ?: $this->input('id');

        return $this->paymentPickup = PaymentPickup::onlyTrashed()->findOrFail(
            $id
        );
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
