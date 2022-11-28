<?php

namespace App\Http\Requests\Company\Costs;

use App\Traits\CompanyInputRequest;
use App\Traits\CostableRequest;
use Illuminate\Foundation\Http\FormRequest;

class TruncateCostsRequest extends FormRequest
{
    use CostableRequest;
    use CompanyInputRequest;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $costable = $this->getCostable();

        return $this->user()
            ->fresh()
            ->can('truncate-cost', $costable);
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
