<?php

namespace App\Http\Requests\ExecuteWorks;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Traits\InputRequest;

use App\Models\ExecuteWork;

class DeleteExecuteWorkRequest extends FormRequest
{
    use InputRequest;

    private $executeWork;

    public function getExecuteWork()
    {
        if ($this->executeWork) return $this->executeWork;

        $id = $this->input('execute_work_id') ?: $this->input('id');
        return $this->executeWork = ExecuteWork::findOrFail($id);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $executeWork = $this->getExecuteWork();
        return Gate::allows('delete-execute-work', $executeWork);
    }

    protected function prepareForValidation()
    {
        $this->merge(['force' => strtobool($this->input('force'))]);
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
