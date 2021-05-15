<?php

namespace App\Http\Requests\RegisterInvitations;

use Illuminate\Foundation\Http\FormRequest;

class SendInvitationRequest extends FormRequest
{
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
        $rules = [
            'invited_email' => ['required', 'string', 'email'],
        ];

        if (request()->input('expiry_time'))
            $rules['expiry_time'] = ['required', 'datetime'];

        return $rules;
    }

    public function onlyInRules()
    {
        return $this->only(array_keys($this->rules()));
    }
}
