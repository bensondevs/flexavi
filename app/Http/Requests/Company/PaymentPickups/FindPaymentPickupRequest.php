<?php

namespace App\Http\Requests\Company\PaymentPickups;

use App\Models\PaymentPickup\PaymentPickup;
use App\Traits\RequestHasRelations;
use Illuminate\Foundation\Http\FormRequest;

class FindPaymentPickupRequest extends FormRequest
{
    use RequestHasRelations;

    /**
     * List of loadable relationships
     *
     * @var array
     */
    private $relationNames = [
        'with_appointment' => true,
        'with_company' => false,
        'with_items' => false
    ];

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
            ->can('view-payment-pickup', $paymentPickup);
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
        $relations = $this->relations();
        return $this->paymentPickup = PaymentPickup::with($relations)->findOrFail($id);
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
