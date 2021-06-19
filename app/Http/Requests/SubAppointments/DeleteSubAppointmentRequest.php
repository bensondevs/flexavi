<?php

namespace App\Http\Requests\SubAppointments;

use Illuminate\Foundation\Http\FormRequest;

use App\Models\SubAppointment;

class DeleteSubAppointmentRequest extends FormRequest
{
    private $appointment;
    private $subAppointment;

    public function getSubAppointment()
    {
        if ($this->subAppointment) return $this->SubAppointment;

        $id = $this->input('id');
        return $this->subAppointment = SubAppointment::findOrFail($id);
    }

    public function getAppointment()
    {
        if ($this->appointment) return $this->appointment;

        $this->appointment = $this->getSubAppointment()->appointment;
        return $this->appointment;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $user = $this->user();
        $appointment = $this->getAppointment();
        $subAppointment = $this->getSubAppointment();

        return $user->hasCompanyPermission($appointment->company_id, 'delete sub appointments');
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
