<?php

namespace App\Http\Requests\Twilio;

use Illuminate\Foundation\Http\FormRequest;

class SmsStatusChangedRequest extends FormRequest
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
            'SmsSid' => ['required', 'string'],
            'SmsStatus' => ['required', 'string'],
        ];
    }

    /**
     * Populate callback data
     *
     * @return array
     */
    public function callbackData(): array
    {
        return [
            'sid' => $this->input('SmsSid'),
            'status' => $this->input('SmsStatus'),
        ];
    }
}
