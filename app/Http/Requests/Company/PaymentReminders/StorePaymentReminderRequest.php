<?php

namespace App\Http\Requests\Company\PaymentReminders;

use App\Models\Appointment\Appointment;
use App\Rules\MoneyValue;
use App\Traits\CompanyInputRequest;
use Illuminate\Foundation\Http\FormRequest;

class StorePaymentReminderRequest extends FormRequest
{
    use CompanyInputRequest;

    /**
     * Found appointment to be processed
     *
     * @var Appointment|null
     */
    private $appointment;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $appointment = $this->getAppointment();

        return $this->user()
            ->fresh()
            ->can('create-payment-reminder', $appointment);
    }

    /**
     * Get Appointment based on supplied input
     *
     * @return Appointment
     */
    public function getAppointment()
    {
        if ($this->appointment) {
            return $this->appointment;
        }
        $id = $this->input('appointment_id');

        return $this->appointment = Appointment::findOrFail($id);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setRules([
            'appointment_id' => ['required', 'string'],
            'reminded_amount' => ['required', new MoneyValue()],
            'transferred_amount' => ['required', new MoneyValue()],
            'reason_not_all' => [
                'required_unless:reminded_amount,' .
                $this->input('transferred_amount'),
            ],
        ]);

        return $this->returnRules();
    }
}
