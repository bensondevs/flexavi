<?php

namespace App\Http\Requests\PaymentPickups;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Models\PaymentPickup;

class UpdatePaymentPickupRequest extends FormRequest
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
        return Gate::allows('edit-payment-pickup', $paymentPickup);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setRules([
            'should_pickup_amount' => ['required', 'numeric'],
            'picked_up_amount' => ['numeric', 'nullable'],
            'reason_not_all' => [
                'string', 
                'nullable', 
                'required_unless:picked_up_amount,' . $this->input('should_pickup_amount')
            ],
            'should_picked_up_at' => ['nullable', 'date'],
            'picked_up_at' => ['nullable', 'date'],
        ]);

        return $this->returnRules();
    }
}
