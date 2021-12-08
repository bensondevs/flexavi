<?php

namespace App\Http\Requests\PaymentPickups;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Models\PaymentPickup;

class FindPaymentPickupRequest extends FormRequest
{
    /**
     * Found payment pickup container
     * 
     * @var \App\Models\PaymentPickup
     */
    private $paymentPickup;

    /**
     * Get payment pickup from supplied input of
     * `payment_pickup_id` or `id`
     * 
     * @return \App\Models\PaymentPickup
     */
    public function getPaymentPickup()
    {
        if ($this->paymentPickup) return $this->paymentPickup;

        $id = $this->input('payment_pickup_id') ?: $this->input('id');
        return $this->paymentPickup = PaymentPickup::findOrFail($id);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $paymentPickup = $this->getPaymentPickup();
        return Gate::allows('view-payment-pickup', $paymentPickup);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }
}
