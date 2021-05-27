<?php

namespace App\Http\Requests\Pricings;

use Illuminate\Foundation\Http\FormRequest;

use App\Rules\FloatValue;

class SavePricingRequest extends FormRequest
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
            'service_name' => ['required', 'string'],
            'price' => ['required', new FloatValue(true)],
            'description' => ['required', 'string'],
        ];
    }
}
