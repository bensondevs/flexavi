<?php

namespace App\Http\Requests\Appointments;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Models\Appointment;

class ExecuteAppointmentRequest extends FormRequest
{
    private $appointment;

    public function getAppointment()
    {
        if ($this->appointment) return $this->appointment;

        $id = $this->input('id');
        $this->appointment = Appointment::findOrFail($id);

        return $this->appointment;
    }

    protected function prepareForValidation()
    {
        $appointment = $this->getAppointment();

        if ($appointment->status != 'created') {
            return response()->json([
                'status' => 'error',
                'message' => 'This appointment can no longer be executed, because the status is already "' . $appointment->status . '"'
            ], 422);
        }

        if ($appointment->start < carbon()->now()) {
            return response()->json([
                'status' => 'error',
                'message' => 'The appointment is already late, please do cancel and reschedule if needed',
            ], 422);
        }
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $appointment = $this->getAppointment();
        return Gate::allows('execute-appointment', $appointment);
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
