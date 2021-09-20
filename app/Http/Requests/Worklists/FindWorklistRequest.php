<?php

namespace App\Http\Requests\Worklists;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Models\Worklist;

use App\Traits\RequestHasRelations;

class FindWorklistRequest extends FormRequest
{
    use RequestHasRelations;

    private $worklist;

    protected $relationNames = [
        'with_workday' => true,
        'with_appointments' => true,
        'with_costs' => true,
        'with_worklist_cars' => true,
        'with_appoint_employees' => true,
        'with_employees' => true,
    ];

    public function getWorklist()
    {
        if ($this->worklist) return $this->worklist;

        $id = $this->input('worklist_id') ?: $this->input('id');
        return $this->worklist = Worklist::findOrFail($id);
    }

    protected function prepareForValidation()
    {
        $this->prepareRelationInputs();
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('view-worklist', $this->getWorklist());
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
}
