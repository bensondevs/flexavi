<?php

namespace App\Http\Requests\Costs;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Models\Cost;

class DeleteCostRequest extends FormRequest
{
    /**
     * Found cost model container
     * 
     * @var \App\Models\Cost|null
     */
    private $cost;

    /**
     * Find Cost or abort 404
     * 
     * @return \App\Models\Cost
     */
    public function getCost()
    {
        if ($this->cost) return $this->cost;

        $id = $this->input('id') ?: $this->input('cost_id');
        return $this->cost = Cost::findOrFail($id);
    }

    /**
     * Prepare input for validation
     * 
     * @return void
     */
    protected function prepareForValidation()
    {
        if ($force = $this->input('force')) {
            $this->merge(['force' => strtobool($force)]);
        }
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $cost = $this->getCost();

        if ($this->input('force')) {
            return Gate::allows('force-delete-cost', $cost);
        }

        return Gate::allows('delete-cost', $cost);
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
