<?php

namespace App\Http\Requests\Company\Costs;

use App\Models\Cost\Cost;
use App\Rules\MoneyValue;
use App\Traits\CompanyInputRequest;
use Illuminate\Foundation\Http\FormRequest;

class SaveCostRequest extends FormRequest
{
    use CompanyInputRequest;

    /**
     * Found cost model container
     *
     * @var Cost|null
     */
    private $cost;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $user = $this->user()->fresh();
        if (!$this->isMethod('POST')) {
            $cost = $this->getCost();
            return $user->can('edit-cost', $cost);
        }

        return $user->can('create-cost');
    }

    /**
     * Get Cost based on supplied input
     *
     * @return Cost
     */
    public function getCost()
    {
        if ($this->cost) {
            return $this->cost;
        }
        $id = $this->input('id');

        return $this->cost = Cost::findOrFail($id);
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
