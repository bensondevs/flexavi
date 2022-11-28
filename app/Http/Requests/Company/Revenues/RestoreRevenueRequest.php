<?php

namespace App\Http\Requests\Company\Revenues;

use App\Models\Revenue\Revenue;
use Illuminate\Foundation\Http\FormRequest;

class RestoreRevenueRequest extends FormRequest
{
    /**
     * Target restored revenue
     *
     * @var Revenue|null
     */
    private $revenue;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $revenue = $this->getRevenue();

        return $this->user()
            ->fresh()
            ->can('restore-revenue', $revenue);
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
        $id = $this->input('revenue_id') ?: $this->input('id');

        return $this->revenue = Revenue::onlyTrashed()->findOrFail($id);
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
