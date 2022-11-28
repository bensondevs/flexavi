<?php

namespace App\Http\Requests\Company\Auths;

use App\Models\User\User;
use Illuminate\Foundation\Http\FormRequest;

class ForgotPasswordRequest extends FormRequest
{
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
        if ($this->user) {
            return $this->user;
        }
        $email = $this->input('email');

        return $this->user = User::findByEmailOrFail($email);
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
        return [
            'email' => ['required', 'string'],
        ];
    }
}
