<?php

namespace App\Http\Requests\Company\Worklists;

use App\Models\Workday\Workday;
use App\Traits\CompanyPopulateRequestOptions;
use App\Traits\RequestHasRelations;
use Illuminate\Foundation\Http\FormRequest;

class PopulateWorkdayWorklistsRequest extends FormRequest
{
    use CompanyPopulateRequestOptions, RequestHasRelations;

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
        'with_pic' => true,
        'with_cars' => true,
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
     * Workday object
     *
     * @var Workday|null
     */
    private $workday;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $workday = $this->getWorkday();

        return $this->user()
            ->fresh()
            ->can('view-any-worklist-workday', $workday);
    }

    /**
     * Get Workday based on supplied input
     *
     * @return Workday
     */
    public function getWorkday()
    {
        if ($this->workday) {
            return $this->workday;
        }

        $id = $this->input('workday_id');

        if (is_null($id)) {

            $date = $this->input('date');
            $company = $this->getCompany();

            return $this->workday = Workday::where('company_id', $company->id)
                ->where('date', $date)->firstOrFail();
        }


        return $this->workday = Workday::findOrFail($id);
    }

    /**
     * Get options
     *
     * @return array
     */
    public function options()
    {
        $this->addWhere([
            'column' => 'workday_id',
            'operator' => '=',
            'value' => $this->workday->id,
        ]);
        if ($this->input('with_total_appointments')) {
            $this->addWithCount('appointments');
        }
        if ($this->input('with_total_employees')) {
            $this->addWithCount('employees');
        }
        if ($this->input('with_total_customers')) {
            $this->addWithCount('customers');
        }
        if ($this->input('with_total_cars')) {
            $this->addWithCount('worklistCars');
        }
        if ($this->input('with_appointments')) {
            $this->addWith('appointments');
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
        if ($this->input('with_cars')) {
            $this->addWith('cars');
        }
        if ($this->input('with_pic')) {
            $this->addWith('pic.user');
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
