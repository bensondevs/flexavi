<?php

namespace App\Http\Requests\SubAppointments;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Models\SubAppointment;

class ProcessSubAppointmentRequest extends FormRequest
{
    private $subAppointment;

    public function getSubAppointment()
    {
        if ($this->subAppointment) return $this->subAppointment;

        $id = $this->input('id') ?: $this->input('sub_appointment_id');
        return $this->subAppointment = SubAppointment::findOrFail($id);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $subAppointment = $this->getSubAppointment();
        return Gate::allows('process-sub-appointment', $subAppointment);
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
