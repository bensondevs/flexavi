<?php

namespace App\Http\Requests\PaymentPickups;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use App\Models\{ 
    PaymentPickup, 
    PaymentPickupable, 
    Invoice, 
    PaymentTerm, 
    Revenue 
};

class SelectPaymentPickupablesRequest extends FormRequest
{
    /**
     * Found payment pickup
     * 
     * @var \App\Models\PaymentPickup
     */
    private $paymentPickup;

    /**
     * Collected all pickupables
     * 
     * @var array
     */
    private $pickupables;

    /**
     * Find payment pickup using supplied parameters
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
        foreach ($request->pickupables as $rawPickupable) {
            $type = $rawPickupable['type'];
            $id = $rawPickupable['id'];
            $model = PaymentPickupable::guessType($type);

            if ($pickupable = $model::find($id)) {
                array_push($pickupables, $pickupable);
            }
        }

        foreach ($request->invoice_ids as $invoiceId) {
            $invoice = Invoice::findOrFail($invoiceId);
            array_push($pickupables, $invoice);
        }

        foreach ($request->revenue_ids as $revenueId) {
            $revenue = Revenue::findOrFail($revenueId);
            array_push($pickupables, $revenue);
        }

        foreach ($request->payment_term_ids as $paymentTermId) {
            $paymentTerm = PaymentTerm::findOrFail($paymentTermId);
            array_push($pickupables, $paymentTerm);
        }

        return $this->pickupables = $pickupables;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('select-collectable-payment-pickup', [
            $this->getPaymentPickup(), 
            $this->getPickupables()
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
