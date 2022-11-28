<?php

namespace App\Http\Requests\Company\ForgotPasswords;

use App\Enums\Auth\ResetPasswordType;
use App\Models\User\PasswordReset;
use App\Models\User\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SendResetCodeRequest extends FormRequest
{

    /**
     * Found User model container
     *
     * @var User|null
     */
    private ?User $user = null;

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
            'user_id' => ['required', 'string'],
            'reset_via' => ['required', Rule::in(ResetPasswordType::getValues())]
        ];
    }

    /**
     * Get input data
     *
     * @return array
     */
    public function passwordResetData(): array
    {
        $user = $this->getCurrentUser();
        $this->setupResetPasswordToken();
        return [
            'email' => $user->email,
            'phone' => $user->phone,
            'reset_via' => $this->input('reset_via'),
        ];
    }

    /**
     * Get User based on supplied input
     *
     * @return User|null
     */
    public function getCurrentUser(): ?User
    {
        if ($this->user) return $this->user;

        $this->user = User::findOrFail($this->input('user_id'));
        return $this->user;
    }

    /**
     * Get Password Reset based on supplied input
     *
     * @return void
     */
    public function setupResetPasswordToken(): void
    {
        $user = $this->getCurrentUser();

        $resetPasswordToken = PasswordReset::where('email', $user->email)->orWhere('phone', $user->phone)->first();

        if (!$resetPasswordToken) {
            return;
        }

        if (!now()->isAfter($resetPasswordToken->expired_at)) {
            abort(422, "Failed to regenerate reset code before the previous reset code expires or is used.");
        }

        $resetPasswordToken->delete();
    }
}
