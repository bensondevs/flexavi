<?php

namespace App\Http\Requests\ExecuteWorkPhotos;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Traits\InputRequest;

class UploadManyExecuteWorkPhotosRequest extends FormRequest
{
    use InputRequest;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
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

    public function photoDataArray()
    {
        $photos = $this->file('photos');
        $descriptions = $this->input('descriptions');

        $dataArray = [];
        foreach ($photos as $index => $photo) {
            array_push($dataArray, [
                'execute_work_id' => $this->getExecuteWork()->id,
                'photo' => $photo,
                'photo_condition_type' => PhotoConditionType::Before,
                'photo_description' => isset($descriptions[$index]) ? 
                    $descriptions[$index] : null,
            ]);
        }

        return $dataArray;
    }
}
