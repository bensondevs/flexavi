<?php

namespace App\Http\Requests\Works;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Traits\PopulateRequestOptions;

use App\Models\WorkContract;

class PopulateContractWorksRequest extends FormRequest
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
        $contract = $this->getWorkContract();

        return Gate::allows('view-any-work', $contract);
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
