<?php

namespace App\Http\Requests\PaymentPickups;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use App\Models\PaymentPickup;

class DeletePaymentPickupRequest extends FormRequest
{
    /**
     * Deleted payment pickup target container
     * 
     * @var \App\Models\PaymentPickup 
     */
    private $paymentPickup;

    /**
     * Get payment pickup from supplied inputted id
     * 
     * @return \App\Models\PaymentPickup
     */
    public function getPaymentPickup()
    {
        if ($this->paymentPickup) return $this->paymentPickup;

        $id = $this->input('payment_pickup_id');
        return $this->paymentPickup = PaymentPickup::findOrFail($id);
    }

    /**
     * Prepare input before validation and or authorization
     * 
     * @return void
     */
    protected function prepareForValidation()
    {
        $force = strtobool($this->input('force'));
        $this->merge(['force' => $force]);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $paymentPickup = $this->getPaymentPickup();

        $force = $this->input('force');
        return Gate::allows(($force ? 'force-' : '') . 'delete-payment-pickup', $paymentPickup);
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
