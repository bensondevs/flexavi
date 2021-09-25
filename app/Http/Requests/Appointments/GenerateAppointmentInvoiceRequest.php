<?php

namespace App\Http\Requests\Appointments;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Models\Appointment;

use App\Enums\Invoice\InvoicePaymentMethod;

class GenerateAppointmentInvoiceRequest extends FormRequest
{
    private $appointment;

    public function getAppointment()
    {
        if ($this->appointment) return $this->appointment;

        $id = $this->input('id') ?: $this->input('appointment_id');
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
        return Gate::allows('generate-invoice-appointment', $appointment);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'payment_method' => [
                'required', 
                'numeric', 
                'min:' . InvoicePaymentMethod::Cash, 
                'max:' . InvoicePaymentMethod::BankTransfer
            ],
        ];
    }
}
