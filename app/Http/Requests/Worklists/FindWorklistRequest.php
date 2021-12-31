<?php

namespace App\Http\Requests\Worklists;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use App\Traits\RequestHasRelations;

use App\Models\Worklist;

class FindWorklistRequest extends FormRequest
{
    use RequestHasRelations;

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
     * Found worklist model container
     * 
     * @var \App\Models\Worklist|null
     */
    private $worklist;
    
    /**
     * Get worklist from supplied input of `worklist_id` or `id`
     * 
     * @return \App\Models\Worklist|abort 404
     */
    public function getWorklist()
    {
        if ($this->worklist) return $this->worklist;

        $id = $this->input('worklist_id') ?: $this->input('id');
        return $this->worklist = Worklist::findOrFail($id);
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
