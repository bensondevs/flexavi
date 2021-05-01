<?php

namespace App\Http\Requests\Auths;

use Illuminate\Foundation\Http\FormRequest;

class CustomerLoginRequest extends FormRequest
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
        return [
            'zipcode' => ['required', 'string'],
            'house_number' => ['required', 'string'],
        ];
    }

    public function onlyInRules()
    {
        return $this->only(array_keys($this->rules()));
    }

    public function messages()
    {
        return [
            'zipcode.required' => 'Please insert Zip Code',
            'zipcode.string' => 'Please insert valid Zip Code',

            'house_number.required' => 'Please insert House Number',
            'house_number.string' => 'Please insert valid House Number',
        ];
    }
}
