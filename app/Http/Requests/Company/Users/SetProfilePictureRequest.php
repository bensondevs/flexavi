<?php

namespace App\Http\Requests\Company\Users;

use App\Rules\Helpers\Media;
use App\Traits\InputRequest;
use Illuminate\Foundation\Http\FormRequest;

class SetProfilePictureRequest extends FormRequest
{
    use InputRequest;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setRules([
            'profile_picture' => [
                'required',
                'image',
                'max:' . Media::MAX_IMAGE_SIZE,
                'mimes:' . Media::imageExtensions(),
            ],
        ]);

        return $this->returnRules();
    }
}
