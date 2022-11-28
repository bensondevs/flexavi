<?php

namespace App\Http\Requests\Company\ForgotPasswords;

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
    private ?User $user = null;

    /**
     * PasswordReset object
     *
     * @var PasswordReset|null
     */
    private ?PasswordReset $resetPasswordToken = null;

    /**
     * Get User based on supplied input
     *
     * @return User|null
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
     * @return PasswordReset|null
     */
    public function getResetPasswordToken(): ?PasswordReset
    {
        if ($this->resetPasswordToken) {
            return $this->resetPasswordToken;
        }

        $token = $this->input('reset_password_token');
        $resetPasswordToken = PasswordReset::where('token', $token)->first();

        if (!$resetPasswordToken) {
            return abort(422, 'Failed to reset password, token not found!');
        }

        if (now() > $resetPasswordToken->expired_at) {
            $resetPasswordToken->delete();
            abort(422, 'Failed to reset password, token expired!');
        }


        return $this->resetPasswordToken = $resetPasswordToken;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'reset_password_token' => ['required', 'string'],
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
