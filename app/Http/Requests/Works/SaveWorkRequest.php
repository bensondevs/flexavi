<?php

namespace App\Http\Requests\Works;

use Illuminate\Foundation\Http\FormRequest;

use App\Rules\FloatValue;

use App\Traits\CompanyInputRequest;

class SaveWorkRequest extends FormRequest
{
    use CompanyInputRequest;

    private $contract;

    public function getWorkContract()
    {
        return $this->contract = $this->contract ?:
            WorkContract::findOrFail($this->input('work_contract_id'));
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if ($this->user()->hasRole('admin')) return true;

        $authorizedAction = $this->authorizeCompanyAction('works');
        $ownedWorkContract = ($this->getWorkContract()->company_id == $this->company->id);

        return ($authorizedAction && $ownedWorkContract);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setRules([
            'work_contract_id' => ['required', 'string'],

            'name' => ['required', 'string'],
            'description' => ['required', 'string'],
            'price' => ['required', new FloatValue(true)],
            'include_tax' => ['required', 'boolean'],
        ]);

        if ($this->input('include_tax') == 1)
            $this->addRule('tax', ['required', new FloatValue(true)]);

        return $this->returnRules();
    }
}
