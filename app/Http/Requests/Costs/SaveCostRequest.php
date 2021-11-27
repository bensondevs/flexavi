<?php

namespace App\Http\Requests\Costs;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Rules\MoneyValue;
use App\Traits\CompanyInputRequest;
use App\Models\Cost;

class SaveCostRequest extends FormRequest
{
    use CompanyInputRequest;

    /**
     * Found cost model container
     * 
     * @var \App\Models\Cost|null
     */
    private $cost;

    /**
     * Find Cost or abort 404
     * 
     * @return \App\Models\Cost
     */
    public function getCost()
    {
        if ($this->cost) return $this->cost;

        $id = $this->input('id');
        return $this->cost = Cost::findOrFail($id);
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
        if (! $this->isMethod('POST')) {
            $cost = $this->getCost();
            return Gate::allows('edit-cost', $cost);
        }

        return Gate::allows('create-cost');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setRules([
            'company_id' => ['required', 'string'],

            'cost_name' => ['required', 'string'],
            'amount' => ['required', new MoneyValue()],
            'paid_amount' => [new MoneyValue()],
        ]);

        return $this->returnRules();
    }
}