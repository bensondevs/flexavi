<?php

namespace App\Http\Requests\Revenues;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Models\Revenue;

class DeleteRevenueRequest extends FormRequest
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
        return $this->revenue = Revenue::withTrashed()->findOrFail($id);
    }

    /**
     * Prepare input to be formatted before validation
     * 
     * @return void
     */
    protected function prepareForValidation()
    {
        $force = $this->input('force');
        $this->merge(['force' => strtobool($force)]);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $force = $this->input('force');
        $revenue = $this->getRevenue();
        return Gate::allows(($force ? 'force-' : '') . 'delete-revenue', $revenue);
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
