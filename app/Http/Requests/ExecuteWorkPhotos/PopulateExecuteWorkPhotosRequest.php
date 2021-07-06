<?php

namespace App\Http\Requests\ExecuteWorkPhotos;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Traits\PopulateRequestOptions;

use App\Models\ExecuteWork;

class PopulateExecuteWorkPhotosRequest extends FormRequest
{
    use PopulateRequestOptions;

    private $executeWork;

    public function getExecuteWork()
    {
        if ($this->executeWork) return $this->executeWork;

        $id = $this->input('execute_work_id');
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
        return Gate::allows('view-any-execute-work-photo', $executeWork);
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

    public function options()
    {
        $this->addWhere([
            'execute_work_id' => $this->getExecuteWork()->id,
        ]);

        return $this->collectOptions();
    }
}
