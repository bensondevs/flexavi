<?php

namespace App\Http\Requests\Works;

use Illuminate\Foundation\Http\FormRequest;

use App\Traits\PopulateRequestOptions;

use App\Models\WorkContract;

class PopulateContractWorkRequest extends FormRequest
{
    use PopulateRequestOptions;

    private $contract;

    public function getWorkContract()
    {
        if ($this->contract) return $this->contract;

        $id = $this->input('contract_id');
        return $this->contract = WorkContract::findOrFail($id);
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

        return $user->hasCompanyPermission($contract->company_id, 'view works');
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

    public function options()
    {
        $this->addWhere([
            'column' => 'work_contract_id',
            'operator' => '=',
            'value' => $this->getWorkContract()->id,
        ]);

        return $this->collectOptions();
    }
}
