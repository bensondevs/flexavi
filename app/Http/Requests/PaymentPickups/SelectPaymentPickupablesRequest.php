<?php

namespace App\Http\Requests\PaymentPickups;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use App\Traits\{
    RequestHasRelations,
    CompanyPopulateRequestOptions
};
use App\Models\PaymentPickup;

class SelectPaymentPickupablesRequest extends FormRequest
{
    use RequestHasRelations;
    use CompanyPopulateRequestOptions;

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
     * This function expect parameter input from front-end as array
     * Each array element given in parameter should contain `type` and `id`
     * The `type` should get model name 
     * (eg: \App\Models\InvoiceItem::class or "\App\Models\Invoice")
     * The ID will be the parameter for finding the result in database
     * 
     * @return array
     */
    public function getPickupables()
    {
        $pickupables = [];
        foreach ($request->pickupables as $rawPickupable) {
            $type = PaymentPickupable::guessType($rawPickupable['type']);
            $id = $rawPickupable['id'];

            if ($pickupable = $type::find($id)) {
                array_push($pickupables, $pickupable);
            }
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
        $paymentPickup = $this->getPaymentPickup();
        $pickupables = $this->getPickupables();
        return Gate::allows('select-collectable-payment-pickup', [$paymentPickup, $pickupables]);
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
