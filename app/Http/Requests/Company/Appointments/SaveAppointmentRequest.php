<?php

namespace App\Http\Requests\Company\Appointments;

use App\Enums\Appointment\AppointmentType;
use App\Models\Appointment\Appointment;
use App\Traits\CompanyInputRequest;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use function App\Http\Requests\Appointments\str_contains;

class SaveAppointmentRequest extends FormRequest
{
    use CompanyInputRequest;

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
        $user = $this->user()->fresh();
        if (!$this->isMethod('POST')) {
            $appointment = $this->getAppointment();
            $user->can('update-appointment', $appointment);
        }

        return $user->can('create-appointment');
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
        if (str_contains($this->url(), "draft"))
            $this->setRules([
                'customer_id' => ['required', 'string', Rule::exists('customers', 'id')],
                'start_date' => ['required', 'date'],
                'end_date' => ['required', 'date'],
                'start_time' => ['required', 'date_format:H:i:s'],
                'end_time' => ['required', 'date_format:H:i:s'],
                'related_appointment_ids' => ['nullable', 'array'],
                'related_appointment_ids.*' => ['nullable', 'string', Rule::exists('appointments', 'id')],
                'include_weekend' => ['boolean'],
                'type' => ['required', 'numeric', Rule::in(AppointmentType::getValues())],
                'description' => ['nullable', 'string'],
                'note' => ['nullable', 'string'],
            ]);
        else
            $this->setRules([
                'customer_id' => ['required', 'string', Rule::exists('customers', 'id')],
                'start_date' => ['required', 'date'],
                'end_date' => ['required', 'date'],
                'start_time' => ['required', 'date_format:H:i:s'],
                'end_time' => ['required', 'date_format:H:i:s'],
                'related_appointment_ids' => ['nullable', 'array'],
                'related_appointment_ids.*' => ['required', 'string', Rule::exists('appointments', 'id')],
                'include_weekend' => ['boolean'],
                'type' => ['required', 'numeric', Rule::in(AppointmentType::getValues())],
                'description' => ['required', 'string'],
                'note' => ['nullable', 'string'],
            ]);

        return $this->returnRules();
    }

    /**
     * Populate appointment data
     *
     * @return array
     */
    public function appointmentData(): array
    {
        $start = $this->input('start_date') . ' ' . $this->input('start_time');
        $end = $this->input('end_date') . ' ' . $this->input('end_time');
        $company = $this->getCompany();
        return [
            'company_id' => $company->id,
            'customer_id' => $this->input('customer_id'),
            'start' => Carbon::createFromFormat('Y-m-d H:i:s', $start),
            'end' => Carbon::createFromFormat('Y-m-d H:i:s', $end),
            'related_appointment_ids' => $this->input('related_appointment_ids') ?: [],
            'include_weekend' => $this->input('include_weekend'),
            'type' => $this->input('type'),
            'description' => $this->input('description'),
            'note' => $this->input('note'),
        ];
    }

    /**
     * Prepare received request values before rules.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        if (!is_array($this->input('related_appointment_ids'))) {
            $relatedAppointmentIds = $this->input('related_appointment_ids');
            $relatedAppointmentIds = json_decode($relatedAppointmentIds, true);
            $this->merge(['related_appointment_ids' => $relatedAppointmentIds]);
        }
    }
}
