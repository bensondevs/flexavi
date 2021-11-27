<?php

namespace App\Http\Requests\Revenues;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Models\Revenue;

class RestoreRevenueRequest extends FormRequest
{
    /**
     * Target restored revenue
     * 
     * @var \App\Models\Revenue
     */
    private $revenue;

    /**
     * Find target restored revenue
     * 
     * @return \App\Models\Revenue
     */
    public function getRevenue()
    {
        if ($this->revenue) return $this->revenue;

        $id = $this->input('revenue_id') ?: $this->input('id');
        return $this->revenue = Revenue::onlyTrashed()->findOrFail($id);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $revenue = $this->getRevenue();
        return Gate::allows('restore-revenue', $revenue);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }
}
