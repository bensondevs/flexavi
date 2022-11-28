<?php

namespace App\Http\Requests\Company\ExecuteWorkPhotos;

use App\Models\ExecuteWork\ExecuteWork;
use App\Traits\PopulateRequestOptions;
use Illuminate\Foundation\Http\FormRequest;

class PopulateExecuteWorkPhotosRequest extends FormRequest
{
    use PopulateRequestOptions;

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
            ->can('view-any-execute-work-photo', $executeWork);
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
        $id = $this->input('execute_work_id');

        return $this->executeWork = ExecuteWork::findOrFail($id);
    }

    /**
     * Get options
     *
     * @return array
     */
    public function options()
    {
        $this->addWhere([
            'column' => 'execute_work_id',
            'value' => $this->getExecuteWork()->id,
        ]);

        return $this->collectOptions();
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
