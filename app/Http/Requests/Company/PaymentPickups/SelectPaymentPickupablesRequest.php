<?php

namespace App\Http\Requests\Company\PaymentPickups;

use App\Models\{Invoice\Invoice,
    PaymentPickup\PaymentPickup,
    PaymentPickup\PaymentPickupable,
    PaymentPickup\PaymentTerm,
    Revenue\Revenue};
use Illuminate\Foundation\Http\FormRequest;

class SelectPaymentPickupablesRequest extends FormRequest
{
    /**
     * Found payment pickup
     *
     * @var PaymentPickup|null
     */
    private $paymentPickup;

    /**
     * Collected all pickupables
     *
     * @var array
     */
    private $pickupables;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()
            ->fresh()
            ->can('select-collectable-payment-pickup', [
                $this->getPaymentPickup(),
                $this->getPickupables(),
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
     * Collect payment pickupables and return as array of models.
     *
     * This function by default, expect parameter input from front-end as array
     * Each array element given in parameter should contain `type` and `id`
     * The `type` should get model name.
     *
     * For alternative which is simpler, input can be inserted as
     * `invoice_ids.*`, `revenue_ids.*`, `payment_term_ids.*`.
     * This function will automatically guess the input as the desired type
     * without specifying the type of the model.
     *
     * (eg: \App\Models\InvoiceItem::class or "\App\Models\Invoice")
     * The ID will be the parameter for finding the result in database
     *
     * @return array
     */
    public function getPickupables()
    {
        $pickupables = [];
        foreach ($this->pickupables as $rawPickupable) {
            $type = $rawPickupable['type'];
            $id = $rawPickupable['id'];
            $model = PaymentPickupable::guessType($type);

            if ($pickupable = $model::find($id)) {
                array_push($pickupables, $pickupable);
            }
        }
        foreach ($this->invoice_ids as $invoiceId) {
            $invoice = Invoice::findOrFail($invoiceId);
            array_push($pickupables, $invoice);
        }
        foreach ($this->revenue_ids as $revenueId) {
            $revenue = Revenue::findOrFail($revenueId);
            array_push($pickupables, $revenue);
        }
        foreach ($this->payment_term_ids as $paymentTermId) {
            $paymentTerm = PaymentTerm::findOrFail($paymentTermId);
            array_push($pickupables, $paymentTerm);
        }

        return $this->pickupables = $pickupables;
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
