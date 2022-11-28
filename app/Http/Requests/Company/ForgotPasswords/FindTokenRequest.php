<?php

namespace App\Http\Requests\Company\ForgotPasswords;

use App\Models\User\PasswordReset;
use Illuminate\Foundation\Http\FormRequest;

class FindTokenRequest extends FormRequest
{
    /**
     * Found Password Reset model container
     *
     * @var PasswordReset|null
     */
    private ?PasswordReset $passwordReset;

    /**
     * Get Password Reset based on supplied input
     *
     * @return PasswordReset|null
     */
    public function getResetPasswordToken(): ?PasswordReset
    {
        if ($this->passwordReset) return $this->passwordReset;

        $resetPasswordToken = PasswordReset::where('email', $this->input('email'))->first();

        if (!$resetPasswordToken) {
            return abort(422, 'Failed to get reset password token, token not found!');
        }

        if (now() > $resetPasswordToken->expired_at) {
            $resetPasswordToken->delete();
            return abort(422, 'Failed to get reset password token, token expired!');
        }


        return $this->passwordReset = $resetPasswordToken;
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
            'email' => ['required', 'email']
        ];
    }
}
