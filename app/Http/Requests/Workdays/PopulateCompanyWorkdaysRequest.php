<?php

namespace App\Http\Requests\Workdays;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Traits\CompanyPopulateRequestOptions;

class PopulateCompanyWorkdaysRequest extends FormRequest
{
    use CompanyPopulateRequestOptions;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('view-any-workday');
    }

    protected function prepareForValidation()
    {
        if ($withWorklists = $this->input('with_worklists')) {
            $this->merge(['with_worklists' => strtobool($withWorklists)]);
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
        if ($withTotalWorklists = $this->input('with_worklists_count')) {
            $this->addWithCount('worklists');
        }
        if ($withWorklists = $this->input('with_worklists')) {
            $this->addWith('worklists');
        }

        if ($withTotalAppointment = $this->input('with_appointments_count')) {
            $this->addWithCount('appointments');
        }
        if ($withAppointments = $this->input('with_appointments')) {
            $this->addWith('appointments');
        }

        $startDate = now()->toDateString();
        if ($start = $this->input('start')) {
            $startDate = $start;
        }
        $this->addWhere([
            'column' => 'date',
            'operator' => '>=',
            'value' => $startDate,
        ]);

        $endDate = now()->copy()->addDays(30)->toDateString();
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
}
