<?php

namespace App\Http\Requests\Company\ForgotPasswords;

use App\Models\User\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FindAccountRequest extends FormRequest
{
    /**
     * Found User model container
     *
     * @var User|null
     */
    private ?User $user = null;

    /**
     * Get User based on supplied input
     *
     * @return User|null
     */
    public function getCurrentUser()
    {
        if ($this->user) return $this->user;

        $this->user = User::where('email', $this->input('email'))->first();
        return $this->user;
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
            'email' => ['required', 'email', Rule::exists('users', 'email')]
        ];
    }
}
