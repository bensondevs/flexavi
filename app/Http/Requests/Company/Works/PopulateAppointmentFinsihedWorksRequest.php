<?php

namespace App\Http\Requests\Company\Works;

use App\Models\Appointment\Appointment;
use App\Traits\CompanyPopulateRequestOptions;
use Illuminate\Foundation\Http\FormRequest;

class PopulateAppointmentFinsihedWorksRequest extends FormRequest
{
    use CompanyPopulateRequestOptions;

    /**
     * Appointment object
     *
     * @var Appointment|null
     */
    private $appointment;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()
            ->fresh()
            ->can('view-any-work');
    }

    /**
     * Get options
     *
     * @return array
     */
    public function options()
    {
        $this->addWhere([
            'column' => 'finished_at_appointment_id',
            'operator' => '=',
            'value' => $this->getAppointment()->id,
        ]);
        if ($this->input('with_finished_at_appointment')) {
            $this->addWith('finishedAtAppointment');
        }

        return $this->collectOptions();
    }

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
        $id = $this->input('appointment_id');

        return $this->appointment = Appointment::findOrFail($id);
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
}
