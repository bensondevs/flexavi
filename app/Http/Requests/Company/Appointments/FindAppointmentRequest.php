<?php

namespace App\Http\Requests\Company\Appointments;

use App\Models\Appointment\Appointment;
use App\Traits\RequestHasRelations;
use Illuminate\Foundation\Http\FormRequest;

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
        'with_related_appointments' => true,
    ];

    /**
     * Appointment model container
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
        $appointment = $this->getAppointment();

        return $this->user()
            ->fresh()
            ->can('view-appointment', $appointment);
    }

    /**
     * Get appointment from the supplied input of
     * `id` or `appointment_id`
     *
     * @return Appointment
     */
    public function getAppointment()
    {
        if ($this->appointment) {
            return $this->appointment;
        }
        $id = $this->input('id') ?: $this->input('appointment_id');

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
}
