<?php

namespace App\Http\Requests\PaymentPickups;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use App\Models\{ PaymentPickup, PaymentPickupable };

class RemovePaymentPickupPickupableRequest extends FormRequest
{
    /**
     * Payment pickup target container
     * 
     * @var \App\Models\PaymentPickup
     */
    private $paymentPickup;

    /**
     * Payment pickupable attached to payment pickup
     * 
     * @var mixed
     */
    private $pickupable;

    /**
     * Get payment pickup from supplied input of 
     * `payment_pickup_id` or `id`
     * 
     * @return \App\Models\PaymentPickup|abort 404
     */
    public function getPaymentPickup()
    {
        if ($this->paymentPickup) return $this->paymentPickup;

        $id = $this->input('payment_pickup_id');
        return $this->paymentPickup = PaymentPickup::findOrFail($id);
    }

    /**
     * Get payment pickupable
     * 
     * @return mixed
     */
    public function getPickupable()
    {
        if ($this->pickupable) return $this->pickupable;

        $id = $this->input('pickupable_id');
        $type = PaymentPickupable::guessType($this->input('pickupable_type'));
        return $this->pickupable = $type->findOrFail($id);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $paymentPickup = $this->getPaymentPickup();
        $pickupable = $this->getPickupable();
        return Gate::allows('remove-pickupable-payment-pickup', [$paymentPickup, $pickupable]);
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
