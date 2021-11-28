<?php

namespace App\Http\Requests\PaymentPickups;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use App\Models\PaymentPickup;

class AddPaymentPickupPickupableRequest extends FormRequest
{
    /**
     * Found payment pickup from executed get function container
     *
     * @var \App\Models\PaymentPickup
     */
    private $paymentPickup;

    /**
     * Found pickupable from executed get function container
     * 
     * @var mixed
     */
    private $pickupable;

    /**
     * Get payment pickup by supplied parameter of
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
     * Get payment pickupable from supplied parameter of
     * `type` and `id`
     * 
     * @return mixed
     */
    public function getPickupable()
    {
        //
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
        return Gate::allows('add-pickupable-payment-pickup', [$paymentPickup, $pickupable]);
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
