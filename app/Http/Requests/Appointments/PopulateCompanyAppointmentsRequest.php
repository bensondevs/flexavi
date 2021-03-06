<?php

namespace App\Http\Requests\Appointments;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Enums\Appointment\AppointmentType;
use App\Enums\Appointment\AppointmentStatus;
use App\Enums\Appointment\AppointmentCancellationVault;

use App\Traits\RequestHasRelations;
use App\Traits\CompanyPopulateRequestOptions;

class PopulateCompanyAppointmentsRequest extends FormRequest
{
    use RequestHasRelations;
    use CompanyPopulateRequestOptions;

    protected $relationNames = [
        'with_worklist' => false,
        'with_workday' => false,
        'with_works' => false,
        'with_costs' => false,
    ];

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('view-any-appointment');
    }

    protected function prepareForValidation()
    {
        if (is_string($this->get('has_subs_only'))) {
            $hasSubsOnly = $this->get('has_subs_only');
            $this->merge(['has_subs_only' => strtobool($hasSubsOnly)]);
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
            'type' => [
                'numeric', 
                'min:' . AppointmentType::Inspection, 
                'max:' . AppointmentType::PaymentReminder
            ],
            'status' => [
                'numeric', 
                'min:' . AppointmentStatus::Created, 
                'max:' . AppointmentStatus::Cancelled
            ],
            'cancellation_vault' => [
                'numeric', 
                'min:' . AppointmentCancellationVault::Roofer, 
                'max:' . AppointmentCancellationVault::Customer
            ],
        ];
    }

    public function options()
    {
        if ($type = $this->get('type')) {
            $this->addWhere([
                'column' => 'type',
                'operator' => '=',
                'value' => $type,
            ]);
        }

        if ($status = $this->get('status')) {
            $this->addWhere([
                'column' => 'status',
                'operator' => '=',
                'value' => $status,
            ]);
        }

        if ($cancellationVault = $this->get('cancellation_vault')) {
            $this->addWhere([
                'column' => 'cancellation_vault',
                'operator' => '=',
                'value' => $cancellationVault,
            ]);
        }

        if ($this->get('has_subs_only')) {
            $this->addWhereHas('subs');
        }

        if ($relations = $this->relations()) {
            $this->setWiths($relations);
        }

        return $this->collectCompanyOptions();
    }
}