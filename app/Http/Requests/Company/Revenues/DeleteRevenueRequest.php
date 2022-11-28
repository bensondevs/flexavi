<?php

namespace App\Http\Requests\Company\Revenues;

use App\Models\Revenue\Revenue;
use Illuminate\Foundation\Http\FormRequest;

class DeleteRevenueRequest extends FormRequest
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
        $force = $this->input('force');
        $revenue = $this->getRevenue();

        return $this->user()
            ->fresh()
            ->can(($force ? 'force-' : '') . 'delete-revenue', $revenue);
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

        return $this->revenue = Revenue::withTrashed()->findOrFail($id);
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
     * Prepare input to be formatted before validation
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $force = $this->input('force');
        $this->merge(['force' => strtobool($force)]);
    }
}
