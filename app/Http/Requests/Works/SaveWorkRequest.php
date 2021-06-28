<?php

namespace App\Http\Requests\Works;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Rules\FloatValue;

use App\Models\Work;
use App\Models\Quotation;
use App\Models\WorkContract;

use App\Traits\CompanyInputRequest;

class SaveWorkRequest extends FormRequest
{
    use CompanyInputRequest;

    private $work;

    private $contract;
    private $quotation;

    public function getWork()
    {
        if ($this->work) return $this->work;

        $id = $this->input('id');
        return $this->work = $this->model = Work::findOrFail($id);
    }

    public function getWorkContract()
    {
        if ($this->contract) return $this->contract;

        $id = $this->input('work_contract_id');
        return $this->contract = WorkContract::findOrFail($id);
    }

    public function getQuotation()
    {
        if ($this->quotation) return $this->quotation;

        $id = $this->input('quotation_id');
        return $this->quotation = Quotation::findOrFail($id);
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'quantity' => (int) $this->input('quantity'),
            'unit_price' => (float) $this->input('unit_price'),
            'include_tax' => filter_var($this->input('include_tax'), FILTER_VALIDATE_BOOLEAN),
        ]);

        if ($this->input('include_tax')) {
            $this->merge(['tax_percentage' => (int) $this->input('tax_percentage')]);
        }
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if ($this->input('work_contract_id')) {
            $quotationOrContract = $this->getWorkContract();
        }

        if ($this->input('quotation_id')) {
            $quotationOrContract = $this->getQuotation();
        }

        if ($this->contract && $this->quotation) {
            if ($this->contract->company_id != $this->quotation->company_id) {
                return false;
            }
        }

        return $this->isMethod('POST') ?
            Gate::allows('create-work', $quotationOrContract) :
            Gate::allows('update-work', $this->getWork());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setRules([
            'quotation_id' => ['string'],
            'work_contract_id' => ['string'],

            'quantity' => ['required', 'integer'],
            'quantity_unit' => ['required', 'string'],
            'description' => ['required', 'string'],
            'unit_price' => ['required', new FloatValue(true)],
            'include_tax' => ['required', 'boolean'],
        ]);

        if ($this->input('include_tax')) {
            $this->addRule('tax_percentage', ['required', new FloatValue(true)]);
        }

        return $this->returnRules();
    }
}
