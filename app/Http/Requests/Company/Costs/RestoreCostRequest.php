<?php

namespace App\Http\Requests\Company\Costs;

use App\Models\Cost\Cost;
use Illuminate\Foundation\Http\FormRequest;

class RestoreCostRequest extends FormRequest
{
    /**
     * Found cost model container
     *
     * @var Cost|null
     */
    private $cost;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $cost = $this->getCost();

        return $this->user()
            ->fresh()
            ->can('restore-cost', $cost);
    }

    /**
     * Get Cost based on supplied input
     *
     * @return Cost
     */
    public function getCost()
    {
        if ($this->cost) {
            return $this->cost;
        }
        $id = $this->input('id') ?: $this->input('cost_id');

        return $this->cost = Cost::withTrashed()->findOrFail($id);
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
