<?php

namespace App\Http\Requests\Company\PaymentPickups;

use App\Models\PaymentPickup\PaymentPickup;
use Illuminate\Foundation\Http\FormRequest;

class DeletePaymentPickupRequest extends FormRequest
{
    /**
     * Deleted payment pickup target container
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
        $force = $this->input('force');

        return $this->user()
            ->fresh()
            ->can(
                ($force ? 'force-' : '') . 'delete-payment-pickup',
                $paymentPickup
            );
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
        $id = $this->input('id') ?? $this->input('payment_pickup_id');

        return $this->paymentPickup = PaymentPickup::findOrFail($id);
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
}
