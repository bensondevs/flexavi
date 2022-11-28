<?php

namespace App\Http\Requests\Company\PaymentReminders;

use App\Models\PaymentPickup\PaymentReminder;
use Illuminate\Foundation\Http\FormRequest;

class RestorePaymentReminderRequest extends FormRequest
{
    /**
     * Restored payment reminder
     *
     * @var PaymentReminder|null
     */
    private $paymentReminder;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $paymentReminder = $this->getTrashedPaymentReminder();

        return $this->user()
            ->fresh()
            ->can('restore-payment-reminder', $paymentReminder);
    }

    /**
     * Get Trashed PaymentReminder based on supplied input
     *
     * @return PaymentReminder
     */
    public function getTrashedPaymentReminder()
    {
        if ($this->paymentReminder) {
            return $this->paymentReminder;
        }
        $id = $this->input('payment_reminder_id') ?: $this->input('id');

        return $this->paymentReminder = PaymentReminder::onlyTrashed()->findOrFail(
            $id
        );
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
