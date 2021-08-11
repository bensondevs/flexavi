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

class RecordCostRequest extends FormRequest
{
    use CostableRequest;
    use CompanyInputRequest;

    private $cost;

    public function getCost()
    {
        if ($this->cost) return $this->cost;

        $id = $this->input('cost_id');
        return $this->cost = Cost::findOrFail($id);
    }

    protected function prepareForValidation()
    {
        if ($recordInWorklist = $this->input('record_in_worklist')) {
            $this->merge(['record_in_worklist' => strtobool($recordInWorklist)]);
        }

        if ($recordInWorkday = $this->input('record_in_workday')) {
            $this->merge(['record_in_workday' => strtobool($recordInWorkday)]);
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

        return Gate::allows('record-cost', [$cost, $costable]);
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
