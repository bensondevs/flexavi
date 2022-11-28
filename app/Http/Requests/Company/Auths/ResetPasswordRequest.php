<?php

namespace App\Http\Requests\Company\Auths;

use App\Models\{User\PasswordReset, User\User};
use App\Rules\{HasLowerCase, HasNumerical, HasSpecialCharacter, HasUpperCase};
use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
{
    /**
     * User object
     *
     * @var User|null
     */
    private $user;

    /**
     * PasswordReset object
     *
     * @var PasswordReset|null
     */
    private $resetPasswordToken;

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
        $resetPasswordToken = $this->getResetPasswordToken();
        $user = $resetPasswordToken->user;

        return $this->user = $user;
    }

    /**
     * Get PasswordReset based on supplied input
     *
     * @return PasswordReset
     */
    public function getResetPasswordToken()
    {
        if ($this->resetPasswordToken) {
            return $this->resetPasswordToken;
        }
        $token = $this->input('reset_password_token');
        $resetPasswordToken = PasswordReset::where('token', $token)->first();
        if (!$resetPasswordToken) {
            return abort(422, 'Failed to reset password, token not found!');
        }

        return $this->resetPasswordToken = $resetPasswordToken;
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
            'password' => [
                'required',
                'string',
                'min:8',
                new HasUpperCase(),
                new HasLowerCase(),
                new HasNumerical(),
                new HasSpecialCharacter(),
            ],
            'confirm_password' => ['required', 'string', 'same:password'],
        ];
    }
}
