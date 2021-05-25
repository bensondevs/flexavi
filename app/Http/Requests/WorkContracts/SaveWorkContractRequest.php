<?php

namespace App\Http\Requests\WorkContracts;

use Illuminate\Foundation\Http\FormRequest;

use App\Rules\JsonArray;
use App\Rules\AmongStrings;
use App\Rules\ExistInCompany;

use App\Traits\CompanyInputRequest;

use App\Models\WorkContract;

class SaveWorkContractRequest extends FormRequest
{
    use CompanyInputRequest;

    private $contract;

    public function getWorkContract()
    {
        return $this->contract = $this->model = ($this->contract) ?:
            WorkContract::findOrFail($this->input('id'));
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->authorizeCompanyAction('work contracts');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setRules([
            'customer_id' => ['required', 'string', new ExistInCompany($this->getCompany(), 'customers')],

            'contract_date_start' => ['required', 'datetime'],
            'contract_date_end' => ['required', 'datetime'],
            'include_weekend' => ['required', 'boolean'],
            'price' => ['required', 'integer'],
            'payment_method' => ['required', 'string'],
            'status' => ['required', 'string', new AmongStrings(['created', 'send', 'signed'])],
            'is_signed' => ['required', 'boolean'],
            'content' => ['required', new JsonArray],
        ]);

        if ($this->isMethod('POST')) {
            $this->addRule('contract_pdf', ['required', 'file', 'mimes:pdf', 'max:10000']);
        }

        return $this->returnRules();
    }

    public function contractData()
    {
        $input = $this->ruleWithCompany();
        unset($input['contract_pdf']);
        return $input;
    }
}
