<?php

namespace App\Http\Requests\Auths;

use Illuminate\Foundation\Http\FormRequest;

use App\Rules\HasUpperCase;
use App\Rules\HasLowerCase;
use App\Rules\HasNumerical;
use App\Rules\HasSpecialCharacter;

use App\Models\RegisterInvitation;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        /* Invited email */
        $insertedEmail = request()->input('email');
        $invitation = RegisterInvitation::findByCode(
            request()->input('invitation_code')
        );

        if (! $invitation) return false;

        return ($invitation->invited_email == $insertedEmail);
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
            'password' => [
                'required', 
                'string', 
                'min:8', 
                new HasUpperCase, 
                new HasLowerCase, 
                new HasNumerical, 
                new HasSpecialCharacter,
            ],
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

    public function messages()
    {
        return [
            'fullname.required' => 'Please insert your Full Name',
            'fullname.string' => 'Please insert correct Full Name',

            'salutation.required' => 'Please select your Salutation',
            'salutation.string' => 'Please select correct Salutation',

            'birth_date.required' => 'Please insert your Birth Date',
            'birth_date.date' => 'Please insert your Birth Date',

            'id_card_type.required' => 'Please select ID Card Type',
            'id_card_type.string' => 'Please select valid ID Card Type',

            'id_card_number.required' => 'Please insert ID Card Number',
            'id_card_number.string' => 'Please insert valid ID Card Number',

            'phone.required' => 'Please insert phone number',
            'phone.string' => 'Please insert phone number',

            'address.required' => 'Please insert your Address',
            'address.string' => 'Please insert your valid Address',

            'profile_picture.required' => 'Please insert your profile picture',
            'profile_picture.file' => 'The profile picture you inserted is not valid file',
            'profile_picture.mimes' => 'This is not allowed file type for the profile picture, please upload only JPG, JPEG, PNG or SVG file',

            'email.required' => 'Please insert your Email',
            'email.string' => 'Please insert valid Email',
            'email.email' => 'Please insert valid Email',
            'email.unique' => 'This email has been taken, please try another',

            'password.required' => 'Please create your Password',
            'password.string' => 'Please create valid Password',
            'password.min' => 'Your password must be more than 8 characters',

            'confirm_password.required' => 'Please insert password verification',
            'confirm_password.string' => 'Please insert valid password verification',
            'confirm_password.same' => 'The Password Confirmation is not the same as created password.'
        ];
    }
}
