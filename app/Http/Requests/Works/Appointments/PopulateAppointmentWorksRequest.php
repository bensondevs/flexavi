<?php

namespace App\Http\Requests\Works\Appointments;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Traits\PopulateRequestOptions;

use App\Models\Appointment;

class PopulateAppointmentWorksRequest extends FormRequest
{
    use PopulateRequestOptions;

    private $appointment;

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
        return Gate::allows('view-any-appointment-work', $appointment);
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

    public function options()
    {
        $this->addWhere([
            'column' => 'appointment_id',
            'operator' => '=',
            'value' => $this->getAppointment()->id,
        ]);

        return $this->collectOptions();
    }
}
