<?php

namespace App\Http\Requests\Company\WorkConditionPhotos;

use Illuminate\Foundation\Http\FormRequest;

class FindWorkConditionPhotoRequest extends FormRequest
{
    /**
     * Get WorkConditionPhoto based on supplied input
     *
     * @return mixed
     */
    public function getWorkConditionPhoto()
    {
        // TODO: complete getWorkConditionPhoto logic
        // return $this->contract =
        //     $this->contract ?:
        //     WorkConditionPhoto::findOrFail($this->input('id'));
        return null;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // TODO: complete authorize logic
        // $user = $this->user();
        // $contract = $this->getWorkConditionPhoto();
        // $actionName = $this->isMethod('GET') ? 'view' : 'delete';
        // $actionObject = 'work condition photos';
        // $action = $actionName . ' ' . $actionObject;
        // $authorizeAction = $user->hasCompanyPermission(
        //     $contract->company_id,
        //     $action
        // );
        // return $authorizeAction;
        return false;
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
