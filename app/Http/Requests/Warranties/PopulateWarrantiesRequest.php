<?php

namespace App\Http\Requests\Warranties;

use Illuminate\Foundation\Http\FormRequest;

use App\Traits\PopulateRequestOptions;

use App\Models\WorkContract;

class PopulateWarrantiesRequest extends FormRequest
{
    use PopulateRequestOptions;

    private $contract;

    public function getWorkContract()
    {
        return $this->contract = ($this->contract) ?:
            WorkContract::findOrFail($this->input('id'));
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $user = $this->user();
        $contract = $this->getWorkContract();

        return $user->hasCompanyPermission(
            $contract->company_id, 
            'view warranties'
        );
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

    public function options()
    {
        return $this->collectOptions();
    }
}
