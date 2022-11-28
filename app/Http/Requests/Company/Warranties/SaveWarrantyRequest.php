<?php

namespace App\Http\Requests\Company\Warranties;

use App\Enums\Appointment\AppointmentType;
use App\Models\{Appointment\Appointment};
use App\Traits\CompanyInputRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaveWarrantyRequest extends FormRequest
{
    use CompanyInputRequest;

    /**
     * Appointment target for the warranty
     *
     * @var Appointment|null
     */
    private $appointment;

    /**
     * Warranted appointment
     *
     * @var Appointment|null
     */
    private $forAppointment;

    /**
     * Get For Appointment based on supplied input
     *
     * @return Appointment
     */
    public function getForAppointment()
    {
        if ($this->forAppointment) {
            return $this->forAppointment;
        }
        $id = $this->input('for_appointment_id');

        return $this->forAppointment = Appointment::findOrFail($id);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()
            ->fresh()->can('create-warranty', $this->getAppointment());
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
        return [
            'company_id' => ['required', 'string'],
            'appointment_id' => ['required', 'string'],
            'warranty_works.*.appointment_id' => [
                'required',
                'string',
                Rule::exists('appointments', 'id')
                    ->whereIn('type', [
                        AppointmentType::ExecuteWork,
                        AppointmentType::Warranty
                    ])
            ],
            'warranty_works.*.works.*.work_warranty_id' => ['required', 'string', Rule::exists('work_warranties', 'id')],
            'warranty_works.*.works.*.company_paid' => ['required', 'numeric'],
        ];
    }

    /**
     * Get warranty data
     *
     * @return array
     */
    public function warrantyData()
    {
        return [
            'company_id' => $this->input('company_id'),
            'appointment_id' => $this->input('appointment_id'),
        ];
    }

    /**
     * Get warranty data
     *
     * @return array
     */
    public function warrantyWorksData()
    {
        return $this->input('warranty_works');
    }

    /**
     * Prepare input before validation
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $company = $this->getCompany();
        $this->merge(['company_id' => $company->id]);
    }
}
