<?php

namespace App\Http\Requests\Works;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Traits\CompanyPopulateRequestOptions;

use App\Models\Appointment;

class PopulateAppointmentFinsihedWorksRequest extends FormRequest
{
    use CompanyPopulateRequestOptions;

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
        return Gate::allows('view-any-work');
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
            'column' => 'finished_at_appointment_id',
            'operator' => '=',
            'value' => $this->getAppointment()->id,
        ]);

        if ($withFinishedAtAppointment = $this->input('with_finished_at_appointment')) {
            $this->addWith('finishedAtAppointment');
        }

        return $this->collectOptions();
    }
}
