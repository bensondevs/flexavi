<?php

namespace App\Http\Requests\Company\Costs\Workdays;

use App\Http\Requests\Company\Costs\SaveCostRequest;
use App\Models\Workday\Workday;
use App\Traits\CompanyInputRequest;
use Illuminate\Foundation\Http\FormRequest;

class SaveWorkdayCostRequest extends FormRequest
{
    use CompanyInputRequest;

    /**
     * Found workday model container
     *
     * @var Workday|null
     */
    private $workday;

    /**
     * Get Workday based on supplied input
     *
     * @return Workday
     */
    public function getWorkday()
    {
        if ($this->workday) {
            return $this->workday;
        }
        $id = $this->input('id') ?: $this->input('workday_id');

        return $this->workday = Workday::findOrFail($id);
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
        $request = new SaveCostRequest();

        return $request->rules();
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
}
