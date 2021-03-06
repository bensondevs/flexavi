<?php

namespace App\Http\Requests\SubAppointments;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Models\SubAppointment;

class DeleteSubAppointmentRequest extends FormRequest
{
    private $appointment;
    private $subAppointment;

    public function getSubAppointment()
    {
        if ($this->subAppointment) return $this->subAppointment;

        $id = $this->input('id') ?: $this->input('sub_appointment_id');
        return $this->subAppointment = SubAppointment::findOrFail($id);
    }

    protected function prepareForValidation()
    {
        if ($force = $this->has('force')) {
            $force = strtobool($this->input('force'));
        }

        $this->merge(['force' => strtobool($force)]);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $subAppointment = $this->getSubAppointment();

        if ($this->input('force')) {
            return Gate::allows('force-delete-sub-appointment', $subAppointment);
        }

        return Gate::allows('delete-sub-appointment', $subAppointment);
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
