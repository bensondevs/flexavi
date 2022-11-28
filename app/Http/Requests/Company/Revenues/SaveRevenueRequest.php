<?php

namespace App\Http\Requests\Company\Revenues;

use App\Models\{Revenue\Revenue, Work\Work};
use App\Rules\MoneyValue;
use App\Traits\CompanyInputRequest;
use Illuminate\Foundation\Http\FormRequest;

class SaveRevenueRequest extends FormRequest
{
    use CompanyInputRequest;

    /**
     * Found revenue model container
     *
     * @return Revenue|null
     */
    private $revenue;

    /**
     * Found revenueable model container
     *
     * @return mixed
     */
    private $revenueable;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $user = $this->user()->fresh();
        if (!$this->isMethod('POST')) {
            $revenue = $this->getRevenue();
            return $user->can('edit-revenue', $revenue);
        }

        return $user->can('create-revenue');
    }

    /**
     * Get Revenue based on supplied input
     *
     * @return Revenue
     */
    public function getRevenue()
    {
        if ($this->revenue) {
            return $this->revenue;
        }
        $id = request()->input('revenue_id');
        $revenue = Revenue::findOrFail($id);
        $this->revenue = $revenue;
        $this->revenueable = $revenue->revenueable;

        return $revenue;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'company_id' => ['required', 'string', 'exists:companies,id'],
            'revenue_name' => ['required', 'string'],
            'amount' => ['required', new MoneyValue()],
            'paid_amount' => [new MoneyValue()],
        ];
    }

    /**
     * Prepare input before validation
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        if (!$this->input('paid_amount')) {
            $amount = $this->input('amount');
            $this->merge(['paid_amount' => $amount]);
        }
        $revenueable = $this->getRevenueable();
        $this->merge(['company_id' => $revenueable->company_id]);
    }

    /**
     * Get revenueable by supplied parameter
     *
     * @return mixed
     */
    public function getRevenueable()
    {
        if ($this->revenueable) {
            return $this->revenueable;
        }
        if ($this->input('work_id')) {
            $id = $this->input('work_id');
            return $this->revenueable = Work::findOrFail($id);
        }
    }
}
