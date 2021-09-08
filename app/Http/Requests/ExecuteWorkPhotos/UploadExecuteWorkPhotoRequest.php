<?php

namespace App\Http\Requests\ExecuteWorkPhotos;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Traits\InputRequest;

use App\Rules\Base64Image;
use App\Rules\Base64MaxSize;

use App\Models\ExecuteWork;

use App\Enums\ExecuteWorkPhoto\PhotoConditionType;

class UploadExecuteWorkPhotoRequest extends FormRequest
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

    protected function prepareForValidation()
    {
        $executeWork = $this->getExecuteWork();
        $this->merge(['execute_work_id' => $executeWork->id]);
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
                'max:' . PhotoConditionType::After
            ],
            'photo' => ['file', 'max:5126', 'mimes:png,jpg,jpeg,svg'],
            'photo_description' => ['required', 'string'],
        ]);

        if (is_base64_string($this->input('photo'))) {
            $this->addRule('photo', [new Base64Image, new Base64MaxSize(5126000)]);
        }

        return $this->returnRules();
    }
}
