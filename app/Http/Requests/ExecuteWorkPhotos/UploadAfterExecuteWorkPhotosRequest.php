<?php

namespace App\Http\Requests\ExecuteWorkPhotos;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Traits\InputRequest;

use App\Models\ExecuteWork;

use App\Enums\ExecuteWorkPhotos\PhotoConditionType;

class UploadAfterExecuteWorkPhotosRequest extends FormRequest
{
    use InputRequest;

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
        return Gate::allows('upload-execute-work-photo', $executeWork);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setRules([
            'execute_work_id' => ['required', 'string'],
            'photos' => ['required', 'array'],
            'descriptions' => ['required', 'array'],
        ]);

        return $this->returnRules();
    }

    public function photoDataArray()
    {
        $photos = $this->photos;
        $descriptions = $this->descriptions;

        $dataArray = [];
        foreach ($photos as $index => $photo) {
            array_push($dataArray, [
                'execute_work_id' => $this->getExecuteWork()->id,
                'photo' => $photo,
                'photo_condition_type' => PhotoConditionType::After,
                'photo_description' => isset($descriptions[$index]) ? 
                    $descriptions[$index] : null,
            ]);
        }

        return $dataArray;
    }
}
