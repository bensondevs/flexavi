<?php

namespace App\Http\Requests\Auths;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
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
        return [
            'fullname' => ['required', 'string'],
            'salutation' => ['required', 'string'],
            'birth_date' => ['required', 'date'],
            'id_card_type' => ['required', 'string'],
            'id_card_number' => ['required', 'string'],
            'phone' => ['required', 'string'],
            'address' => ['required', 'string'],
            'profile_picture' => ['required', 'file', 'mimes:jpg,jpeg,svg,png'],
            'email' => ['required', 'string', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'confirm_password' => ['required', 'string', 'same:password'],
        ];
    }

    public function onlyInRules()
    {
        $rules = array_keys($this->rules());
        unset($rules['profile_picture']);
        unset($rules['confirm_password']);

        return $this->only($rules);
    }
}
