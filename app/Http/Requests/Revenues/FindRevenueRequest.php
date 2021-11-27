<?php

namespace App\Http\Requests\Revenues;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Models\Revenue;

class FindRevenueRequest extends FormRequest
{
    /**
     * Found revenue from inserted id
     * 
     * @var \App\Models\Revenue
     */
    private $revenue;

    /**
     * Find revenue
     * 
     * @return \App\Models\Revenue|null
     */
    public function getRevenue()
    {
        if ($this->revenue) return $this->revenue;

        $id = $this->input('id') ?: $this->input('revenue_id');
        return $this->revenue = Revenue::findOrFail($id);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $revenue = $this->getRevenue();
        return Gate::allows('view-revenue', $revenue);
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
