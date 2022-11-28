<?php

namespace App\Http\Requests\Company\Workdays;

use App\Traits\CompanyPopulateRequestOptions;
use App\Traits\RequestHasRelations;
use Illuminate\Foundation\Http\FormRequest;

class PopulateCompanyWorkdaysRequest extends FormRequest
{
    use CompanyPopulateRequestOptions, RequestHasRelations;


    /**
     * List of configurable relationships
     *
     * @var array
     */
    protected $relationNames = [
        'with_company' => false,
        'with_worklists.appointments.customer.address' => true,
        'with_appointments.customer.address' => true,
        'with_costs' => false,
        'with_receipts' => false,
        'with_worklists_costs' => false,
        'with_employees' => false,
        'with_unplanned_appointments.customer.address' => true,
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
            ->can('view-any-workday');
    }

    /**
     * Get options
     *
     * @return array
     */
    public function options()
    {
        if ($this->input('with_worklists_count')) {
            $this->addWithCount('worklists');
        }
        if ($this->input('with_worklists')) {
            $this->addWith('worklists');
        }
        if ($this->input('with_appointments_count')) {
            $this->addWithCount('appointments');
        }
        if ($this->input('with_unplanned_appointments_count')) {
            $this->addWithCount('unplannedAppointments');
        }
        if ($this->input('with_appointments')) {
            $this->addWith('appointments');
        }
        if ($this->input('with_unplanned_appointments')) {
            $this->addWith('unplannedAppointments');
        }

        $startDate = month_start_date();
        if ($start = $this->input('start')) {
            $startDate = $start;
        }

        $this->addWhere([
            'column' => 'date',
            'operator' => '>=',
            'value' => $startDate,
        ]);

        $endDate = now()
            ->toDateString();
        if ($end = $this->input('end')) {
            $endDate = $end;
        }
        $this->addWhere([
            'column' => 'date',
            'operator' => '<=',
            'value' => $endDate,
        ]);

        return $this->collectCompanyOptions();
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
     * Prepare for validation
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        if ($withWorklists = $this->input('with_worklists')) {
            $this->merge(['with_worklists' => strtobool($withWorklists)]);
        }
    }
}
