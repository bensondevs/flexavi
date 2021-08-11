<?php

namespace App\Http\Requests\Costs;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Traits\CostableRequest;
use App\Traits\CompanyInputRequest;

use App\Models\Cost;
use App\Models\Workday;
use App\Models\Worklist;
use App\Models\Appointment;

class RecordManyCostsRequest extends FormRequest
{
    use CostableRequest;
    use CompanyInputRequest;

    private $costIds;

    protected function prepareForValidation()
    {
        $costIds = $this->input('cost_ids');
        if (! is_array($costIds)) {
            $this->merge(['cost_ids' => json_decode($costIds)]);
        }
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $costable = $this->getCostable();
        return Gate::allows('record-many-cost', $costable);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setRules([
            'cost_ids' => ['required', 'array'],
            'cost_ids.*' => ['required', 'exists:costs,id'],
        ]);

        return $this->returnRules();
    }

    public function costIdsArray()
    {
        return $this->input('cost_ids');
    }
}
