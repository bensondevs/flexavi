<?php

namespace App\Http\Requests\Company\Users;

use App\Models\User\User;
use App\Traits\InputRequest;
use Illuminate\Foundation\Http\FormRequest;

class SaveUserRequest extends FormRequest
{
    use InputRequest;

    /**
     * User object
     *
     * @var User|null
     */
    private $user;

    /**
     * Get User based on supplied input
     *
     * @return User
     */
    public function getCurrentUser()
    {
        return $this->user = $this->model =
            $this->user ?: User::findOrFail($this->input('id'));
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
            'phone' => ['required', 'string', 'unique:users,phone'],
            'address' => ['required', 'string', 'unique:users,address'],
            'email' => ['required', 'string', 'email', 'unique:users,email'],
        ]);

        return $this->returnRules();
    }
}
