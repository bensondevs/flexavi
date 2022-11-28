<?php

namespace App\Http\Requests\Company\ExecuteWorkPhotos;

use App\Enums\ExecuteWorkPhoto\PhotoConditionType;
use App\Models\ExecuteWork\ExecuteWork;
use App\Traits\InputRequest;
use Illuminate\Foundation\Http\FormRequest;

class UploadAfterExecuteWorkPhotosRequest extends FormRequest
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
            ->can('upload-execute-work-photo', $executeWork);
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

    /**
     * Get photo data
     *
     * @return array
     */
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
                'photo_description' => isset($descriptions[$index])
                    ? $descriptions[$index]
                    : null,
            ]);
        }

        return $dataArray;
    }
}
