<?php

namespace App\Http\Requests\Company\ExecuteWorks;

use App\Models\ExecuteWork\ExecuteWork;
use App\Traits\InputRequest;
use Illuminate\Foundation\Http\FormRequest;

class MarkFinishedWorkRequest extends FormRequest
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
            ->can('mark-finish-execute-work', $executeWork);
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
        $id = $this->input('id') ?: $this->input('execute_work_id');

        return $this->executeWork = ExecuteWork::findOrFail($id);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setRules([
            'finish_note' => ['string'],
        ]);

        return $this->returnRules();
    }
}
