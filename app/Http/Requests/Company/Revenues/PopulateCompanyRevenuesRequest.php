<?php

namespace App\Http\Requests\Company\Revenues;

use App\Traits\CompanyPopulateRequestOptions;
use Illuminate\Foundation\Http\FormRequest;

class PopulateCompanyRevenuesRequest extends FormRequest
{
    use CompanyPopulateRequestOptions;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()
            ->fresh()
            ->can('view-any-revenue');
    }

    /**
     * Get options
     *
     * @return array
     */
    public function options()
    {
        if ($this->has('min_amount')) {
            $minAmount = $this->input('min_amount');
            $this->addWhere([
                'column' => 'amount',
                'operator' => '>=',
                'value' => $minAmount,
            ]);
        }
        if ($this->has('max_amount')) {
            $maxAmount = $this->input('max_amount');
            $this->addWhere([
                'column' => 'amount',
                'operator' => '<=',
                'value' => $maxAmount,
            ]);
        }
        if ($this->has('min_paid_amount')) {
            $minPaidAmount = $this->input('min_paid_amount');
            $this->addWhere([
                'column' => 'paid_amount',
                'operator' => '>=',
                'value' => $minPaidAmount,
            ]);
        }
        if ($this->has('max_paid_amount')) {
            $maxPaidAmount = $this->input('max_paid_amount');
            $this->addWhere([
                'column' => 'paid_amount',
                'operator' => '<=',
                'value' => $maxPaidAmount,
            ]);
        }

        return $this->collectCompanyOptions();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [];
    }
}
