<?php

namespace App\Http\Requests\Revenues;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Rules\MoneyValue;
use App\Traits\CompanyInputRequest;
use App\Models\Revenue;

class SaveRevenueRequest extends FormRequest
{
    use CompanyInputRequest;

    /**
     * Found revenue model container
     * 
     * @return \App\Models\Revenue|null
     */
    private $revenue;

    /**
     * Found revenueable model container
     * 
     * @return \App\Models\Revenueable
     */
    private $revenueable;

    /**
     * Find revenue by supplied parameter "revenue_id" or "id"
     * 
     * @return \App\Models\Revenue|null
     */
    public function getRevenue()
    {
        if ($this->revenue) return $this->revenue;

        $id = $request->input('revenue_id');
        $revenue = Revenue::findOrFail($id);
        $this->revenue = $revenue;
        $this->revenueable = $revenue->revenueable;

        return $revenue;
    }

    /**
     * Find revenueable by supplied parameter
     * 
     * @return mixed
     */
    public function getRevenueable()
    {
        if ($this->revenueable) return $this->revenueable;

        if ($this->input('work_id')) {
            $id = $this->input('work_id');
            return $this->revenueable = Work::findOrFail($id);
        }
    }

    /**
     * Prepare input before validation
     * 
     * @return void
     */
    protected function prepareForValidation()
    {
        if (! $this->input('paid_amount')) {
            $amount = $this->input('amount');
            $this->merge(['paid_amount' => $amount]);
        }

        $revenueable = $this->getRevenueable();
        $this->merge(['company_id' => $revenueable->company_id]);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (! $this->isMethod('POST')) {
            $revenue = $this->getRevenue();
            return Gate::allows('edit-revenue', $revenue);
        }

        return Gate::allows('create-revenue');
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
            'amount' => ['required', new MoneyValue],
            'paid_amount' => [new MoneyValue],
        ];
    }
}
