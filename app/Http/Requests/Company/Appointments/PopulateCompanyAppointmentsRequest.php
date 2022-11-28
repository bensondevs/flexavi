<?php

namespace App\Http\Requests\Company\Appointments;

use App\Enums\Appointment\AppointmentCancellationVault;
use App\Enums\Appointment\AppointmentStatus;
use App\Enums\Appointment\AppointmentType;
use App\Traits\CompanyPopulateRequestOptions;
use App\Traits\RequestHasRelations;
use Illuminate\Foundation\Http\FormRequest;

class PopulateCompanyAppointmentsRequest extends FormRequest
{
    use RequestHasRelations;
    use CompanyPopulateRequestOptions;

    /**
     * Define the relation names
     *
     * @var array
     */
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
        return $this->user()
            ->fresh()
            ->can('view-any-appointment');
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
                'max:' . AppointmentType::PaymentReminder,
            ],
            'status' => [
                'numeric',
                'min:' . AppointmentStatus::Created,
                'max:' . AppointmentStatus::Cancelled,
            ],
            'cancellation_vault' => [
                'numeric',
                'min:' . AppointmentCancellationVault::Roofer,
                'max:' . AppointmentCancellationVault::Customer,
            ],
        ];
    }

    /**
     * Get options
     *
     * @return array
     */
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

    /**
     * Prepare for validation
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        if (is_string($this->get('has_subs_only'))) {
            $hasSubsOnly = $this->get('has_subs_only');
            $this->merge(['has_subs_only' => strtobool($hasSubsOnly)]);
        }
    }
}
