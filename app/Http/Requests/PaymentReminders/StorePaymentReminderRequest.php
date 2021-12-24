<?php

namespace App\Http\Requests\PaymentReminders;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use App\Traits\CompanyInputRequest;

use App\Rules\MoneyValue;

use App\Models\Appointment;

class StorePaymentReminderRequest extends FormRequest
{
    use CompanyInputRequest;

    /**
     * Found appointment to be processed
     * 
     * @var \App\Models\Appointment|null
     */
    private $appointment;

    /**
     * Get appointment by supplied input of "appointment_id"
     * 
     * @return \App\Models\Appointment|abort 404
     */
    public function getAppointment()
    {
        if ($this->appointment) return $this->appointment;

        $id = $this->input('appointment_id');
        return $this->appointment = Appointment::findOrFail($id);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $appointment = $this->getAppointment();
        return Gate::allows('create-payment-reminder', $appointment);
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
            'reminded_amount' => ['required', new MoneyValue],
            'transferred_amount' => ['required', new MoneyValue],
            'reason_not_all' => [
                'required_unless:reminded_amount,' . $this->input('transferred_amount'),
            ],
        ]);
        return $this->returnRules();
    }
}
