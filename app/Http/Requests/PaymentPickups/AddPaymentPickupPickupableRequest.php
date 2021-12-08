<?php

namespace App\Http\Requests\PaymentPickups;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use App\Models\{ PaymentPickup, PaymentPickupable, Invoice, Revenue, PaymentTerm };

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
