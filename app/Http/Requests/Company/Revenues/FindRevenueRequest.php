<?php

namespace App\Http\Requests\Company\Revenues;

use App\Models\Revenue\Revenue;
use Illuminate\Foundation\Http\FormRequest;

class FindRevenueRequest extends FormRequest
{
    /**
     * Found revenue from inserted id
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
            ->can('view-revenue', $revenue);
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
        $id = $this->input('id') ?: $this->input('revenue_id');

        return $this->revenue = Revenue::findOrFail($id);
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
