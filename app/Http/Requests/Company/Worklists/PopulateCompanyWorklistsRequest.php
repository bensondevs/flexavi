<?php

namespace App\Http\Requests\Company\Worklists;

use App\Traits\{CompanyPopulateRequestOptions, RequestHasRelations};
use Illuminate\Foundation\Http\FormRequest;

class PopulateCompanyWorklistsRequest extends FormRequest
{
    use RequestHasRelations;
    use CompanyPopulateRequestOptions;

    /**
     * List of configurable relationships
     *
     * @var array
     */
    protected $relationNames = [
        'with_workday' => true,
        'with_appointments' => true,
        'with_costs' => false,
        'with_appoint_employees' => false,
        'with_employees' => true,
        'with_user' => true,
        'with_car' => true,
    ];

    /**
     * List of configurable relationships count
     *
     * @var array
     */
    protected $relationCountNames = [
        'with_appointments_count' => true,
        'with_costs_count' => false,
        'with_appoint_employees_count' => false,
        'with_employees_count' => false,
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
            ->can('view-any-worklist');
    }

    /**
     * Query options for configuring query
     *
     * @return array
     */
    public function options()
    {
        $date = $this->input('date');
        if ($date) {
            $this->addWhereHas('workday', [
                [
                    'column' => 'date',
                    'operator' => '=',
                    'value' => $date
                ]
            ]);
        } else {

            $fromDate = month_start_date();
            $toDate = now();

            if ($this->input('start')) $fromDate = $this->input('start');

            if ($this->input('end')) $toDate = $this->input('end');

            $this->addWhereHas('workday', [
                [
                    'column' => 'date',
                    'operator' => '>=',
                    'value' => $fromDate,
                ],
                [
                    'column' => 'date',
                    'operator' => '<=',
                    'value' => $toDate,
                ],
            ]);
        }

        if ($this->input('with_total_appointments')) {
            $this->addWithCount('appointments');
        }
        if ($this->input('with_total_employees')) {
            $this->addWithCount('employees');
        }
        if ($this->input('with_total_customers')) {
            $this->addWithCount('customers');
        }
        if ($this->input('with_total_car')) {
            $this->addWithCount('car');
        }
        if ($this->input('with_appointments')) {
            $this->addWith('appointments');
        }
        if ($this->input('with_workday')) {
            $this->addWith('workday');
        }
        if ($this->input('with_sub_appointments')) {
            $this->addWith('appointments.subs');
        }
        if ($this->input('with_employees')) {
            $this->addWith('employees.user');
        }
        if ($this->input('with_costs')) {
            $this->addWith('costs');
        }
        if ($this->input('with_car')) {
            $this->addWith('car');
        }
        if ($this->input('with_user')) {
            $this->addWith('user');
        }

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
     * Prepare input for validation
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->prepareRelationInputs();
    }
}
