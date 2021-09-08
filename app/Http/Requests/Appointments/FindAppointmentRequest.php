<?php

namespace App\Http\Requests\Appointments;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Models\Appointment;

class FindAppointmentRequest extends FormRequest
{
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
        foreach ($this->relationNames as $requestKey => $defaultValue) {
            if ($this->has($requestKey)) {
                $this->merge([$requestKey => strtobool($this->input($requestKey))]);
            }
        }
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

    public function relations()
    {
        $relations = [];
        foreach ($this->relationNames as $name => $defaultValue) {
            $relationName = str_replace('with_', '', $name);
            $relationName = str_camel_case($relationName);

            /*
                Get request key name, if not set then get the default value 
            */
            if ($this->input($name, $defaultValue)) {
                $relations[] = $relationName;
            }
        }

        return $relations;
    }
}
