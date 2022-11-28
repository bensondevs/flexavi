<?php

namespace App\Http\Requests\Company\SubAppointments;

use App\Models\Appointment\SubAppointment;
use Illuminate\Foundation\Http\FormRequest;

class DeleteSubAppointmentRequest extends FormRequest
{
    /**
     * SubAppointment object
     *
     * @var SubAppointment|null
     */
    private $subAppointment;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $subAppointment = $this->getSubAppointment();
        $user = $this->user()->fresh();
        if ($this->input('force')) {
            return $user->can('force-delete-sub-appointment', $subAppointment);
        }

        return $user->can('delete-sub-appointment', $subAppointment);
    }

    /**
     * Get SubAppointment based on supplied input
     *
     * @return SubAppointment
     */
    public function getSubAppointment()
    {
        if ($this->subAppointment) {
            return $this->subAppointment;
        }
        $id = $this->input('id') ?: $this->input('sub_appointment_id');

        return $this->subAppointment = SubAppointment::findOrFail($id);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [];
    }

    /**
     * Prepare for validation
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        if ($force = $this->has('force')) {
            $force = strtobool($this->input('force'));
        }
        $this->merge(['force' => strtobool($force)]);
    }
}
