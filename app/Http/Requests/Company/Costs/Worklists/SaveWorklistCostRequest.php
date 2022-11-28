<?php

namespace App\Http\Requests\Company\Costs\Worklists;

use App\Http\Requests\Company\Costs\SaveCostRequest;
use App\Models\Worklist\Worklist;
use App\Traits\CompanyInputRequest;
use Illuminate\Foundation\Http\FormRequest;

class SaveWorklistCostRequest extends FormRequest
{
    use CompanyInputRequest;

    /**
     * Found worklist model container
     *
     * @var Worklist|null
     */
    private $worklist;

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
        $id = $this->input('id') ?: $this->input('worklist_id');

        return $this->worklist = Worklist::findOrFail($id);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()
            ->fresh()
            ->can('create-cost');
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

    /**
     * Prepare input for validation
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        if (!$this->has('record_in_workday')) {
            $this->merge(['record_in_workday' => true]);
        }
        $this->merge(['company_id' => $this->getCompany()->id]);
    }
}
