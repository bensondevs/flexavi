<?php

namespace App\Http\Requests\Works\Appointments;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Http\Requests\Works\SaveWorkRequest;

use App\Traits\InputRequest;

use App\Models\Appointment;

class SaveAppointmentWorkRequest extends FormRequest
{
    use InputRequest;

    /**
     * Found appointment model container
     * 
     * @var \App\Models\Appointment|null
     */
    private $appointment;

    /**
     * Find Appointment or abort 404
     * 
     * @return \App\Models\Appointment
     */
    public function getAppointment()
    {
        if ($this->appointment) return $this->appointment;

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
        $appointment = $this->getAppointment();
        return Gate::allows('create-work');
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
