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

class UnrecordCostRequest extends FormRequest
{
    use CostableRequest;
    use CompanyInputRequest;

    protected function prepareForValidation()
    {
        if ($recordInWorklist = $this->input('unrecord_from_worklist')) {
            $this->merge(['unrecord_from_worklist' => strtobool($recordInWorklist)]);
        }

        if ($recordInWorkday = $this->input('unrecord_from_workday')) {
            $this->merge(['unrecord_from_workday' => strtobool($recordInWorkday)]);
        }
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $cost = $this->getCost();
        $costable = $this->getCostable();

        return Gate::allows('unrecord-cost', [$cost, $costable]);
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
