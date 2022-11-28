<?php

namespace App\Http\Requests\Company\Appointments;

use App\Models\Worklist\Worklist;
use App\Traits\{CompanyPopulateRequestOptions, RequestHasRelations};
use Illuminate\Foundation\Http\FormRequest;

class PopulateWorklistAppointmentsRequest extends FormRequest
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
     * Worklist object
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
            ->can('view-any-appointment', $worklist);
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
        $id = $this->input('worklist_id');

        return $this->worklist = Worklist::findOrFail($id);
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

        return $this->collectOptions();
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
        $this->prepareRelationInputs();
    }
}
