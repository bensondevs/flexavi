<?php

namespace App\Http\Requests\Company\ExecuteWorkPhotos;

use App\Enums\ExecuteWorkPhoto\PhotoConditionType;
use App\Models\ExecuteWork\ExecuteWork;
use App\Rules\Helpers\Media;
use App\Traits\InputRequest;
use Illuminate\Foundation\Http\FormRequest;

class UploadExecuteWorkPhotoRequest extends FormRequest
{
    use InputRequest;

    /**
     * ExecuteWork object
     *
     * @return ExecuteWork|null
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
            'photo_condition_type' => [
                'required',
                'numeric',
                'min:' . PhotoConditionType::Before,
                'max:' . PhotoConditionType::After,
            ],
            'photo' => [
                'required',
                'image',
                'max:' . Media::MAX_IMAGE_SIZE,
                'mimes:' . Media::imageExtensions(),
            ],
            'photo_description' => ['required', 'string'],
        ]);

        return $this->returnRules();
    }

    /**
     * Prepare for validation
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $executeWork = $this->getExecuteWork();
        $this->merge(['execute_work_id' => $executeWork->id]);
    }
}
