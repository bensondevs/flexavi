<?php

namespace App\Http\Requests\Appointments;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Traits\RequestHasRelations;

use App\Models\Appointment;

class FindAppointmentRequest extends FormRequest
{
    use RequestHasRelations;

    /**
     * List of loadable relations
     * 
     * @var array
     */
    protected $relationNames = [
        'with_finished_works' => true,
        'with_customer' => true,
        'with_subs' => true,
        'with_works' => true,
        'with_worklists' => true,
        'with_workdays' => true,
        'with_calculation' => true,
        'with_employees' => true,
        'with_revenues' => false,
        'with_costs' => false,
        'with_quotation' => false,
        'with_execute_works' => false,
        'with_warranties' => false,
        'with_payment_reminder' => false,
        'with_invoice' => false,
    ];

    /**
     * Appointment model container
     * 
     * @var \App\Models\Appointment|null
     */
    private $appointment;

    /**
     * Get appointment from the supplied input of
     * `id` or `appointment_id`
     * 
     * @return \App\Models\Appointment|abort 404
     */
    public function getAppointment()
    {
        if ($this->appointment) return $this->appointment;

        $id = $this->input('id') ?: $this->input('appointment_id');
        return $this->appointment = Appointment::findOrFail($id);
    }

    /**
     * Prepare input before validation
     * 
     * This will process the $relationNames to make the result load the
     * data according to configurations
     * 
     * @return void 
     */
    protected function prepareForValidation()
    {
        $this->prepareRelationInputs();
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $appointment = $this->getAppointment();

        return Gate::allows('view-appointment', $appointment);
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
