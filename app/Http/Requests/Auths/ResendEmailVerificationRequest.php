<?php

namespace App\Http\Requests\Auths;

use Illuminate\Foundation\Http\FormRequest;

use App\Models\User;

class ResendEmailVerificationRequest extends FormRequest
{
    private $user;

    public function getUser()
    {
        if ($this->user) return $this->user;

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
        $user = $this->getUser();
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
            //
        ];
    }
}
