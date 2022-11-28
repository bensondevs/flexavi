<?php

namespace App\Http\Requests\Company\PaymentPickups;

use App\Models\{Invoice\Invoice,
    PaymentPickup\PaymentPickup,
    PaymentPickup\PaymentPickupable,
    PaymentPickup\PaymentTerm,
    Revenue\Revenue};
use Illuminate\Foundation\Http\FormRequest;

class AddPaymentPickupPickupableRequest extends FormRequest
{
    /**
     * Found payment pickup from executed get function container
     *
     * @var PaymentPickup|null
     */
    private $paymentPickup;

    /**
     * Found pickupable from executed get function container
     *
     * @var mixed
     */
    private $pickupable;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $paymentPickup = $this->getPaymentPickup();
        $pickupable = $this->getPickupable();

        return $this->user()
            ->fresh()
            ->can('add-pickupable-payment-pickup', [
                $paymentPickup,
                $pickupable,
            ]);
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
        if ($this->pickupable) {
            return $this->pickupable;
        }
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
                $type = PaymentPickupable::guessType(
                    $this->input('pickupable_type')
                );
                break;
        }

        return $this->pickupable = $type::findOrFail($id);
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
