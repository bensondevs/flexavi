<?php

namespace App\Http\Requests\Warranties;

use Illuminate\Foundation\Http\FormRequest;

use App\Models\Warranty;

class FindWarrantyRequest extends FormRequest
{
    private $warranty;

    public function getWarranty()
    {
        return $this->warranty = $this->warranty ?:
            Warranty::findOrFail($this->input('id'));
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $user = $this->user();
        $warranty = $this->getWarranty();
        $contract = $warranty->workContract;
        $company = $contract->company;

        $actionName = ($this->isMethod('GET')) ? 'view' : 'delete';
        $actionObject = 'warranties';
        $action = $actionName . ' ' . $actionObject;
        $authorizeAction = $user->hasCompanyPermission($company->id, $action);

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
