<?php

namespace App\Http\Requests\Company\ExecuteWorkPhotos;

use App\Models\ExecuteWork\ExecuteWorkPhoto;
use App\Traits\InputRequest;
use Illuminate\Foundation\Http\FormRequest;

class DeleteExecuteWorkPhotoRequest extends FormRequest
{
    use InputRequest;

    /**
     * ExecuteWorkPhoto object
     *
     * @var ExecuteWorkPhoto|null
     */
    private $executeWorkPhoto;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $photo = $this->getExecuteWorkPhoto();

        return $this->user()
            ->fresh()
            ->can('delete-execute-work-photo', $photo);
    }

    /**
     * Get ExecuteWorkPhoto based on supplied input
     *
     * @return ExecuteWorkPhoto
     */
    public function getExecuteWorkPhoto()
    {
        if ($this->executeWorkPhoto) {
            return $this->executeWorkPhoto;
        }
        $id = $this->input('id') ?: $this->input('execute_work_photo_id');

        return $this->executeWorkPhoto = ExecuteWorkPhoto::withTrashed()->findOrFail(
            $id
        );
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
        $force = strtobool($this->input('force'));
        $this->merge(['force' => $force]);
    }
}
