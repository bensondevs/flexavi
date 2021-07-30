<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;

use App\Rules\Base64Image;

use App\Traits\InputRequest;

class SetProfilePictureRequest extends FormRequest
{
    use InputRequest;

    public $user;

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
            'profile_picture' => ['required', 'file', 'mimes:jpg,jpeg,png,svg'],
        ]);

        if (is_base64_string($this->input('profile_picture'))) {
            $this->rules['profile_picture'] = ['required', new Base64Image()];
        }

        return $this->returnRules();
    }
}
