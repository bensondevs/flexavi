<?php

namespace App\Http\Requests\Company\Costs;

use App\Traits\CompanyInputRequest;
use App\Traits\CostableRequest;
use Illuminate\Foundation\Http\FormRequest;

class UnrecordCostRequest extends FormRequest
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
            ->can('unrecord-cost', [$cost, $costable]);
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
        if ($recordInWorklist = $this->input('unrecord_from_worklist')) {
            $this->merge([
                'unrecord_from_worklist' => strtobool($recordInWorklist),
            ]);
        }
        if ($recordInWorkday = $this->input('unrecord_from_workday')) {
            $this->merge([
                'unrecord_from_workday' => strtobool($recordInWorkday),
            ]);
        }
    }
}
