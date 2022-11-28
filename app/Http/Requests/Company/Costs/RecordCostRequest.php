<?php

namespace App\Http\Requests\Company\Costs;

use App\Traits\CompanyInputRequest;
use App\Traits\CostableRequest;
use Illuminate\Foundation\Http\FormRequest;

class RecordCostRequest extends FormRequest
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
        $cost = $this->getCost();
        $costable = $this->getCostable();

        return $this->user()
            ->fresh()
            ->can('record-cost', [$cost, $costable]);
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
        if ($recordInWorklist = $this->input('record_in_worklist')) {
            $this->merge([
                'record_in_worklist' => strtobool($recordInWorklist),
            ]);
        }
        if ($recordInWorkday = $this->input('record_in_workday')) {
            $this->merge(['record_in_workday' => strtobool($recordInWorkday)]);
        }
    }
}
