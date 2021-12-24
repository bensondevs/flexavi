<?php

namespace App\Http\Requests\PaymentReminders;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Models\PaymentReminder;

use App\Traits\RequestHasRelations;

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
     * @var  \App\Models\PaymentReminder|null
     */
    private $paymentReminder;

    /**
     * Get payment reminder by supplied input of
     * "payment_reminder_id" or "id"
     * 
     * @return  \App\Models\PaymentReminder|abort 404
     */
    public function getPaymentReminder()
    {
        if ($this->paymentReminder) return $this->paymentReminder;

        $id = $this->input('payment_reminder_id') ?: $this->input('id');
        return $this->paymentReminder = PaymentReminder::findOrFail($id);
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

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $paymentReminder = $this->getPaymentReminder();
        return Gate::allows('view-payment-reminder', $paymentReminder);
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
