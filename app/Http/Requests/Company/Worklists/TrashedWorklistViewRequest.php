<?php

namespace App\Http\Requests\Company\Worklists;

use App\Models\Worklist\Worklist;
use App\Traits\CompanyPopulateRequestOptions;
use App\Traits\RequestHasRelations;
use Illuminate\Foundation\Http\FormRequest;

class TrashedWorklistViewRequest extends FormRequest
{
    use RequestHasRelations, CompanyPopulateRequestOptions;

    /**
     * List of configurable relationships
     *
     * @var array
     */
    protected $relationNames = [
        'with_workday' => true,
        'with_appointments.employees' => true,
        'with_appointments.subs' => true,
        'with_appointments.customer' => true,
        'with_worklist_cars' => false,
        'with_costs' => false,
        'with_appoint_employees' => false,
        'with_employees.user' => true,
        'with_car' => true,
        'with_user' => true,
    ];

    /**
     * Found worklist model container
     *
     * @var Worklist|null
     */
    private $trashedWorklist;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $trashedWorklist = $this->getTrashedWorklist();

        return $this->user()
            ->fresh()
            ->can('view-worklist', $trashedWorklist);
    }

    /**
     * Get Worklist based on supplied input
     *
     * @return Worklist
     */
    public function getTrashedWorklist()
    {
        if ($this->trashedWorklist) {
            return $this->trashedWorklist;
        }
        $id = $this->input('worklist_id') ?: $this->input('id');

        return $this->trashedWorklist = Worklist::onlyTrashed()->findOrFail(
            $id
        );
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
