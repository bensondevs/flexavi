<?php

namespace App\Http\Requests\Company\Workdays;

use App\Models\Workday\Workday;
use App\Traits\CompanyInputRequest;
use App\Traits\RequestHasRelations;
use Illuminate\Foundation\Http\FormRequest;

class FindWorkdayRequest extends FormRequest
{
    use RequestHasRelations, CompanyInputRequest;

    /**
     * List of configurable relationships
     *
     * @var array
     */
    protected $relationNames = [
        'with_company' => false,
        'with_worklists.appointments.customer.address' => true,
        'with_appointments.customer.address' => true,
        'with_subAppointments' => false,
        'with_receipts' => false,
        'with_worklists_costs' => false,
        'with_worklists.employees' => false,
        'with_worklists.employees.user' => false,
        'with_employees' => false,
        'with_cars' => false,
        'with_customers' => false,
        'with_unplanned_appointments.customer.address' => true,

        'with_revenues' => false,
        'with_costs' => false,
        'with_calculation' => false,
    ];

    /**
     * Define the relation count names
     *
     * @var array
     */
    protected $relationCountNames = [
        'with_worklists_count' => false,
        'with_unplannedAppointments_count' => false,
        'with_appointments_count' => false,
        'with_subAppointments_count' => true
    ];

    /**
     * Found workday model container
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
        return $this->user()
            ->fresh()
            ->can('view-workday', $this->getWorkday());
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

        $id = $this->input('workday_id') ?: $this->input('id');
        if (is_null($id)) {

            $date = $this->input('date');
            $company = $this->getCompany();

            return $this->workday = Workday::withCount($this->getRelationCounts())->where('company_id', $company->id)
                ->where('date', $date)->firstOrFail();
        }
        return $this->workday = Workday::withCount($this->getRelationCounts())->findOrFail($id);
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
}
