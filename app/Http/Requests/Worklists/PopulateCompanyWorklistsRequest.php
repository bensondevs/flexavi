<?php

namespace App\Http\Requests\Worklists;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Traits\{
    RequestHasRelations,
    CompanyPopulateRequestOptions
};

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
        'with_worklist_cars' => false,
        'with_costs' => false,
        'with_appoint_employees' => false,
        'with_employees' => false,
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
        return Gate::allows('view-any-worklist');
    }

    /**
     * Prepare input for validation
     * 
     * @return void
     */
    protected function prepareForValidation()
    {
        if ($start = $this->input('start')) {
            $start = carbon()->parse($start)->toDateString();
            $this->merge(['start' => $start]);
        }

        if ($end = $this->input('end')) {
            $end = carbon()->parse($end)->toDateString();
            $this->merge(['end' => $end]);
        }

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

    /**
     * Query options for configuring query
     * 
     * @return array
     */
    public function options()
    {
        if (! $fromDate = $this->input('from_date')) {
            $fromDate = month_start_date();              
        }

        if (! $toDate = $this->input('to_date')) {
            $toDate = current_date();
        }

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
            ]
        ]);

        if ($withTotalAppointments = $this->input('with_total_appointments')) {
            $this->addWithCount('appointments');
        }

        return $this->collectCompanyOptions();
    }
}