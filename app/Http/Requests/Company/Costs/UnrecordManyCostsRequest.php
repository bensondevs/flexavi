<?php

namespace App\Http\Requests\Company\Costs;

use App\Traits\CompanyInputRequest;
use App\Traits\CostableRequest;
use Illuminate\Foundation\Http\FormRequest;

class UnrecordManyCostsRequest extends FormRequest
{
    use CostableRequest;
    use CompanyInputRequest;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $costable = $this->getCostable();

        return $this->user()
            ->fresh()
            ->can('unrecord-many-cost', $costable);
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

    /**
     * Get cost identifiers
     *
     * @return array
     */
    public function costIdsArray()
    {
        return $this->input('cost_ids');
    }

    /**
     * Prepare for validation
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $costIds = $this->input('cost_ids');
        if (!is_array($costIds)) {
            $this->merge(['cost_ids' => json_decode($costIds)]);
        }
    }
}
