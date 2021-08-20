<?php

namespace App\Http\Requests\Worklists;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Traits\CompanyPopulateRequestOptions;

class PopulateCompanyWorklistsRequest extends FormRequest
{
    use CompanyPopulateRequestOptions;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('view-any-worklist');
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'with_appointments' => strtobool($this->input('with_appointments')),
        ]);

        if ($start = $this->input('start')) {
            $this->merge(['start' => carbon()->parse($start)->toDateString()]);
        }

        if ($end = $this->input('end')) {
            $this->merge(['end' => carbon()->parse($end)->toDateString()]);
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

        if ($withAppointments = $this->input('with_appointments')) {
            $this->addWith('appointments');
        }

        return $this->collectCompanyOptions();
    }
}