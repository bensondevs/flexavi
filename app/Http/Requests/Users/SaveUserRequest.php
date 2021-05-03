<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;

use App\Rules\AmongStrings;

use App\Models\User;

class SaveUserRequest extends FormRequest
{
    private $user;

    public function getUser()
    {
        return $this->user ?: 
            User::findOrFail(request()->input('id'));
    }

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
        $rules = [
            'fullname' => ['required', 'string'],
            'salutation' => ['required', 'string', new AmongStrings(['Mr.', 'Mrs.', 'Ms.'])],
            'birth_date' => ['required', 'date'],
            'id_card_type' => [
                'required', 
                'string', 
                new AmongStrings([
                    'id_card',
                    'driving_license',
                    'passport',
                ]
            )],
            'id_card_number' => ['required', 'string', 'unique:users,id_card_number'],
            'phone' => ['required', 'string', 'unique:users,phone'],
            'address' => ['required', 'string', 'unique:users,address'],
            'email' => ['required', 'string', 'email', 'unique:users,email'],
            
        ];

        if (request()->isMethod('PUT') || request()->isMethod('PATCH')) {
            $user = $this->getUser();

            if (request()->input('id_card_number') == $user->id_card_number)
                unset($rules['id_card_number']);

            if (request()->input('phone') == $user->phone)
                unset($rules['phone']);

            if (request()->input('address') == $user->address)
                unset($rules['address']);

            if (request()->input('email') == $user->email) 
                unset($rules['email']);
        }

        return $rules;
    }

    public function onlyInRules()
    {
        return $this->only(array_keys($this->rules()));
    }
}
