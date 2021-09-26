<?php

namespace App\Http\Requests\Auths;

use Illuminate\Foundation\Http\FormRequest;

use App\Rules\HasUpperCase;
use App\Rules\HasLowerCase;
use App\Rules\HasNumerical;
use App\Rules\HasSpecialCharacter;
use App\Rules\Base64Image;

use App\Models\User;
use App\Models\PasswordReset;

class ResetPasswordRequest extends FormRequest
{
    private $user;
    private $resetPasswordToken;

    public function getUser()
    {
        if ($this->user) return $this->user;

        $resetPasswordToken = $this->getResetPasswordToken();
        $user = $resetPasswordToken->user;
        return $this->user = $user;
    }

    public function getResetPasswordToken()
    {
        if ($this->resetPasswordToken) {
            return $this->resetPasswordToken;
        }

        $token = $this->input('reset_password_token');
        $resetPasswordToken = PasswordReset::where('token', $token)->first();
        if (! $resetPasswordToken) {
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
                new HasUpperCase, 
                new HasLowerCase, 
                new HasNumerical, 
                new HasSpecialCharacter,
            ],
            'confirm_password' => ['required', 'string', 'same:password'],
        ];
    }
}
