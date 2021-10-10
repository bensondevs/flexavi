<?php

namespace App\Http\Requests\Costs\Worklists;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Http\Requests\Costs\SaveCostRequest;

use App\Traits\CompanyInputRequest;

use App\Models\Worklist;

class SaveWorklistCostRequest extends FormRequest
{
    use CompanyInputRequest;

    /**
     * Found worklist model container
     * 
     * @var \App\Models\Worklist|null
     */
    private $worklist;

    /**
     * Find Worklist or abort 404
     * 
     * @return \App\Models\Worklist
     */
    public function getWorklist()
    {
        if ($this->worklist) return $this->worklist;

        $id = $this->input('id') ?: $this->input('worklist_id');
        return $this->worklist = Worklist::findOrFail($id);
    }

    /**
     * Prepare input for validation
     * 
     * @return void
     */
    protected function prepareForValidation()
    {
        if (! $this->has('record_in_workday')) {
            $this->merge(['record_in_workday' => true]);
        }

        $this->merge(['company_id' => $this->getCompany()->id]);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('create-cost');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = (new SaveCostRequest())->rules();
        $rules['record_in_workday'] = ['boolean'];
        return $rules;
    }
}
