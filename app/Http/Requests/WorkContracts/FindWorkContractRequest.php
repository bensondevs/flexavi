<?php

namespace App\Http\Requests\WorkContracts;

use Illuminate\Foundation\Http\FormRequest;

class FindWorkContractRequest extends FormRequest
{
    private $contract;

    public function getWorkContract()
    {
        return $this->contract = $this->model = ($this->contract) ?:
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
        $company = $contract->company;

        $actionName = ($this->isMethod('GET')) ? 'view' : 'delete';
        $actionObject = 'work contracts';
        $action = $actionName . ' ' . $actionObject;
        $authorizeAction = $user->hasCompanyPermission(
            $company->id, $action
        );
        
        if ($this->isMethod('GET')) return $authorizeAction;

        $authorizeRecord = ($company->id == $contract->company_id);
        return ($authorizeAction && $authorizeRecord);
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
