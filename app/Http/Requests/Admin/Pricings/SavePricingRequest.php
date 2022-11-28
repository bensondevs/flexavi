<?php

namespace App\Http\Requests\Admin\Pricings;

use App\Rules\FloatValue;
use Illuminate\Foundation\Http\FormRequest;

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
