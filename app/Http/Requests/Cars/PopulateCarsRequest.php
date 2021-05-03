<?php

namespace App\Http\Requests\Cars;

use Illuminate\Foundation\Http\FormRequest;

class PopulateCarsRequest extends FormRequest
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
            'company_id' => ['required', 'string', 'exists:companies,id'],
        ];

        if (request()->input('free_only')) {
            $rules['free_only'] = ['required', 'boolean'];
        }

        return $rules;
    }
}
