<?php

namespace App\Http\Requests\Company\Worklists;

use App\Models\Worklist\Worklist;
use App\Traits\CompanyPopulateRequestOptions;
use App\Traits\RequestHasRelations;
use Illuminate\Foundation\Http\FormRequest;

class FindWorklistRequest extends FormRequest
{
    use RequestHasRelations, CompanyPopulateRequestOptions;

    /**
     * List of configurable relationships
     *
     * @var array
     */
    protected $relationNames = [
        'with_workday' => true,
        'with_appointments' => true,
        'with_car' => true,
        'with_user' => true,
        'with_appoint_employees' => false,
        'with_employees.user' => true,

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
        'with_appointments_count' => false,
        'with_appoint_employees_count' => false,
        'with_employees_count' => false,
    ];

    /**
     * Found worklist model container
     *
     * @var Worklist|null
     */
    private $worklist;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $worklist = $this->getWorklist();

        return $this->user()
            ->fresh()
            ->can('view-worklist', $worklist);
    }

    /**
     * Get Worklist based on supplied input
     *
     * @return Worklist
     */
    public function getWorklist()
    {
        if ($this->worklist) {
            return $this->worklist;
        }
        $id = $this->input('worklist_id') ?: $this->input('id');

        return $this->worklist = Worklist::query()->withCount($this->getRelationCounts())->findOrFail($id);
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
     * This will configure inputs to set the relationships
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->prepareRelationInputs();
    }
}
