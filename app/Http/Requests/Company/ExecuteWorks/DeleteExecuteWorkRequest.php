<?php

namespace App\Http\Requests\Company\ExecuteWorks;

use App\Models\ExecuteWork\ExecuteWork;
use App\Traits\InputRequest;
use Illuminate\Foundation\Http\FormRequest;

class DeleteExecuteWorkRequest extends FormRequest
{
    use InputRequest;

    /**
     * ExecuteWork object
     *
     * @var ExecuteWork|null
     */
    private $executeWork;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $executeWork = $this->getExecuteWork();

        return $this->user()
            ->fresh()
            ->can('delete-execute-work', $executeWork);
    }

    /**
     * Get ExecuteWork based on supplied input
     *
     * @return ExecuteWork
     */
    public function getExecuteWork()
    {
        if ($this->executeWork) {
            return $this->executeWork;
        }
        $id = $this->input('execute_work_id') ?: $this->input('id');

        return $this->executeWork = ExecuteWork::findOrFail($id);
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

    /**
     * Prepare for validation
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge(['force' => strtobool($this->input('force'))]);
    }
}
