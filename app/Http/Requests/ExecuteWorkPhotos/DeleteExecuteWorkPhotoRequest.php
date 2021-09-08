<?php

namespace App\Http\Requests\ExecuteWorkPhotos;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Traits\InputRequest;

use App\Models\ExecuteWorkPhoto;

class DeleteExecuteWorkPhotoRequest extends FormRequest
{
    use InputRequest;

    private $executeWorkPhoto;

    public function getExecuteWorkPhoto()
    {
        if ($this->executeWorkPhoto) {
            return $this->executeWorkPhoto;
        }

        $id = $this->input('id') ?: $this->input('execute_work_photo_id');
        return $this->executeWorkPhoto = ExecuteWorkPhoto::withTrashed()->findOrFail($id);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $photo = $this->getExecuteWorkPhoto();
        return Gate::allows('delete-execute-work-photo', $photo);
    }

    protected function prepareForValidation()
    {
        $force = strtobool($this->input('force'));
        $this->merge(['force' => $force]);
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
