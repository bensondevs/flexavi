<?php

namespace App\Http\Requests\Company\Appointments\Employees;

use App\Models\Appointment\Appointment;
use App\Traits\CompanyPopulateRequestOptions;
use App\Traits\RequestHasRelations;
use Illuminate\Foundation\Http\FormRequest;

class PopulateAppointmentEmployeesRequest extends FormRequest
{
    use RequestHasRelations;
    use CompanyPopulateRequestOptions;

    /**
     * Define the relation names
     *
     * @var array
     */
    private $relationNames = [
        'with_appointment' => false,
        'with_user' => true,
    ];

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
            ->can('view-any-employee');
    }

    /**
     * Get options
     *
     * @return array
     */
    public function options()
    {
        $this->addWhere([
            'column' => 'appointment_id',
            'value' => $this->getAppointment()->id,
        ]);
        $this->setWiths($this->relations());

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
