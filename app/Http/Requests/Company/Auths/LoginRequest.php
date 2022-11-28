<?php

namespace App\Http\Requests\Company\Auths;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
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
            'email' => ['required', 'string', 'exists:users,email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Get validation messages
     *
     * @return array
     */
    public function messages()
    {
        return [
            'email.required' => 'Email is required',
            'email.string' => 'Email should be string value',
            'email.exists' => 'Email you inserted is not exist in database',
            'password.required' => 'Password is required',
            'password.string' => 'Password input should be string',
        ];
    }
}
