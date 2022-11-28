<?php

namespace App\Http\Requests\Company\Works\Appointments;

use App\Http\Requests\Company\Works\SaveWorkRequest;
use App\Models\Appointment\Appointment;
use App\Traits\InputRequest;
use Illuminate\Foundation\Http\FormRequest;

class SaveAppointmentWorkRequest extends FormRequest
{
    use InputRequest;

    /**
     * Found appointment model container
     *
     * @var Appointment|null
     */
    private $appointment;

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
        $id = $this->input('id');

        return $this->appointment = Appointment::findOrFail($id);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()
            ->fresh()
            ->can('create-work');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $saveRequest = new SaveWorkRequest();
        $rules = $saveRequest->rules();

        return $rules;
    }
}
