<?php

namespace App\Http\Requests\Appointments;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Models\Worklist;

use App\Traits\{
    RequestHasRelations,
    CompanyPopulateRequestOptions
};

class PopulateWorklistAppointmentsRequest extends FormRequest
{
    use RequestHasRelations;
    use CompanyPopulateRequestOptions;

    protected $relationNames = [
        'with_worklist' => false,
        'with_workday' => false,
        'with_works' => false,
        'with_costs' => false,
    ];

    private $worklist;

    public function getWorklist()
    {
        if ($this->worklist) return $this->worklist;

        $id = $this->input('worklist_id');
        return $this->worklist = Worklist::findOrFail($id);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $worklist = $this->getWorklist();
        return Gate::allows('view-any-appointment', $worklist);
    }

    protected function prepareForValidation()
    {
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
}
