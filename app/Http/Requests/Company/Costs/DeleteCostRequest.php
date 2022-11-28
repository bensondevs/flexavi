<?php

namespace App\Http\Requests\Company\Costs;

use App\Models\Cost\Cost;
use Illuminate\Foundation\Http\FormRequest;

class DeleteCostRequest extends FormRequest
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
        $user = $this->user()->fresh();
        if ($this->input('force')) {
            return $user->can('force-delete-cost', $cost);
        }

        return $user->can('delete-cost', $cost);
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

        return $this->cost = Cost::findOrFail($id);
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

    /**
     * Prepare input for validation
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $force = $this->input('force');
        $this->merge(['force' => strtobool($force)]);
    }
}
