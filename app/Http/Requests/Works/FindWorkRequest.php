<?php

namespace App\Http\Requests\Works;

use Illuminate\Foundation\Http\FormRequest;

class FindWorkRequest extends FormRequest
{
    private $work;

    public function getWork()
    {
        return $this->work = $this->work ?: 
            Work::findOrFail($this->input('id'));
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $user = $this->user();
        $work = $this->getWork();

        $actionName = ($this->isMethod('GET')) ? 'view' : 'delete';
        $actionObject = 'works';
        return $user->hasCompanyPermission(
            $work->company_id, 
            $actionName . ' ' . $actionObject
        );
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
