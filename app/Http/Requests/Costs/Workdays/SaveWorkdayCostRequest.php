<?php

namespace App\Http\Requests\Costs\Workdays;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Http\Requests\Costs\SaveCostRequest;

use App\Traits\CompanyInputRequest;

use App\Models\Workday;

class SaveWorkdayCostRequest extends FormRequest
{
    use CompanyInputRequest;

    /**
     * Found workday model container
     * 
     * @var \App\Models\Workday|null
     */
    private $workday;

    /**
     * Find Workday or abort 404
     * 
     * @return \App\Models\Workday
     */
    public function getWorkday()
    {
        if ($this->workday) return $this->workday;

        $id = $this->input('id') ?: $this->input('workday_id');
        return $this->workday = Workday::findOrFail($id);
    }

    /**
     * Prepare input for validation
     * 
     * @return void
     */
    protected function prepareForValidation()
    {
        $company = $this->getCompany();
        $this->merge(['company_id' => $company->id]);
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
        $request = new SaveCostRequest();
        return $request->rules();
    }
}
