<?php

namespace App\Http\Requests\Appointments;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Traits\RequestHasRelations;

use App\Models\Appointment;

class FindAppointmentRequest extends FormRequest
{
    use RequestHasRelations;

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

    private $appointment;

    public function getAppointment()
    {
        if ($this->appointment) return $this->appointment;

        $id = $this->input('id') ?: $this->input('appointment_id');
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

        return Gate::allows('view-appointment', $appointment);
    }

    protected function prepareForValidation()
    {
        $this->prepareRelationInputs();
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
