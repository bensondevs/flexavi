<?php

namespace App\Http\Requests\PaymentPickups;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use App\Models\{ PaymentPickup, PaymentPickupable, Invoice, Revenue, PaymentTerm };

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
     * Get payment pickupable from supplied parameter of
     * `type` and `id` or `invoice_id` or `revenue_id`
     * or `payment_term_id`
     * 
     * @return mixed
     */
    public function getPickupable()
    {
        if ($this->pickupable) return $this->pickupable;

        switch (true) {
            case $this->has('invoice_id'):
                $id = $this->input('invoice_id');
                $type = Invoice::class;
                break;

            case $this->has('revenue_id'):
                $id = $this->input('revenue_id');
                $type = Revenue::class;
                break;

            case $this->has('payment_term_id'):
                $id = $this->input('payment_term_id');
                $type = PaymentTerm::class;
                break;
            
            default:
                $id = $this->input('pickupable_id');
                $type = PaymentPickupable::guessType($this->input('pickupable_type'));
                break;
        }

        
        return $this->pickupable = $type::findOrFail($id);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('remove-pickupable-payment-pickup', [
            $this->getPaymentPickup(), 
            $this->getPickupable()
        ]);
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
