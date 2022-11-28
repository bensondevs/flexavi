<?php

namespace App\Http\Requests\Company\PaymentReminders;

use App\Models\PaymentPickup\PaymentReminder;
use App\Traits\RequestHasRelations;
use Illuminate\Foundation\Http\FormRequest;

class FindPaymentReminderRequest extends FormRequest
{
    use RequestHasRelations;

    /**
     * List configuration for relationship loaded
     *
     * @var array
     */
    private $relationNames = [
        'with_company' => false,
        'with_appointment' => false,
        'with_reminderables' => false,
    ];

    /**
     * Found payment reminder
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
        $paymentReminder = $this->getPaymentReminder();

        return $this->user()
            ->fresh()
            ->can('view-payment-reminder', $paymentReminder);
    }

    /**
     * Get PaymentReminder based on supplied input
     *
     * @return PaymentReminder
     */
    public function getPaymentReminder()
    {
        if ($this->paymentReminder) {
            return $this->paymentReminder;
        }
        $id = $this->input('payment_reminder_id') ?: $this->input('id');

        return $this->paymentReminder = PaymentReminder::findOrFail($id);
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
     * Prepare inputs for validation
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->prepareRelationInputs();
    }
}
