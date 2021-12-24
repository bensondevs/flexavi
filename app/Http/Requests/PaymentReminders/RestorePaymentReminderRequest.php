<?php

namespace App\Http\Requests\PaymentReminders;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Models\PaymentReminder;

class RestorePaymentReminderRequest extends FormRequest
{
    /**
     * Restored payment reminder
     * 
     * @var \App\Models\PaymentReminder
     */
    private $paymentReminder;

    /**
     * Get the deleted payment reminder
     * from the supplied input of 
     * "payment_reminder_id" or "id"
     */
    public function getTrashedPaymentReminder()
    {
        if ($this->paymentReminder) return $this->paymentReminder;

        $id = $this->input('payment_reminder_id') ?: $this->input('id');
        return $this->paymentReminder = PaymentReminder::onlyTrashed()
            ->findOrFail($id);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $paymentReminder = $this->getTrashedPaymentReminder();
        return Gate::allows('restore-payment-reminder', $paymentReminder);
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
