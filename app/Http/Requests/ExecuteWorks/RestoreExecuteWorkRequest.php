<?php

namespace App\Http\Requests\ExecuteWorks;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Models\ExecuteWork;

class RestoreExecuteWorkRequest extends FormRequest
{
    private $executeWork;

    public function getExecuteWork()
    {
        if ($this->executeWork) return $this->executeWork;

        $id = $this->input('execute_work_id') ?: $this->input('id');
        return $this->executeWork = ExecuteWork::withTrashed()->findOrFail($id);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $executeWork = $this->getExecuteWork();
        return Gate::allows('restore-execute-work', $executeWork);
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
