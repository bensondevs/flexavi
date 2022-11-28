<?php

namespace App\Http\Requests\Company\Companies;

use Illuminate\Foundation\Http\FormRequest;

class PopulateCompaniesRequest extends FormRequest
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
     * Get options
     *
     * @return array
     */
    public function options()
    {
        $withs = [];
        $wheres = [];
        $whereHases = [];
        $whereHases['owner'] = [
            [
                'column' => 'user_id',
                'value' => $this->user()->id,
            ],
        ];

        return [
            'withs' => $withs,
            'wheres' => $wheres,
            'where_hases' => $whereHases,
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [];
    }
}
