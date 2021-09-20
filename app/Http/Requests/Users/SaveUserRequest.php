<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;

use App\Models\User;

use App\Enums\User\UserIdCardType;

use App\Traits\InputRequest;

class SaveUserRequest extends FormRequest
{
    use InputRequest;

    private $user;

    public function getUser()
    {
        return $this->user = $this->model = $this->user ?: 
            User::findOrFail($this->input('id'));
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
        $this->setRules([
            'fullname' => ['required', 'string'],
            'birth_date' => ['required', 'date'],
            'id_card_type' => [
                'required', 
                'numeric', 
                'min:' . UserIdCardType::NationalIdCard,
                'max:' . UserIdCardType::DrivingLicense,
            ],
            'id_card_number' => ['required', 'string', 'unique:users,id_card_number'],
            'phone' => ['required', 'string', 'unique:users,phone'],
            'address' => ['required', 'string', 'unique:users,address'],
            'email' => ['required', 'string', 'email', 'unique:users,email'],
        ]);

        return $this->returnRules();
    }
}
