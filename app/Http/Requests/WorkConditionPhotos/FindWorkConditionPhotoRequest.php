<?php

namespace App\Http\Requests\WorkConditionPhotos;

use Illuminate\Foundation\Http\FormRequest;

use App\Models\WorkConditionPhoto;

class FindWorkConditionPhotoRequest extends FormRequest
{
    private $contract;

    public function getWorkConditionPhoto()
    {
        return $this->contract = ($this->contract) ?:
            WorkConditionPhoto::findOrFail($this->input('id'));
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $user = $this->user();
        $contract = $this->getWorkConditionPhoto();

        $actionName = ($this->isMethod('GET')) ? 'view' : 'delete';
        $actionObject = 'work condition photos';
        $action = $actionName . ' ' . $actionObject;
        $authorizeAction = $user->hasCompanyPermission(
            $contract->company_id, $action
        );
        
        return $authorizeAction;
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
