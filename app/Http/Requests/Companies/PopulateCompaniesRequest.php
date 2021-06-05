<?php

namespace App\Http\Requests\Companies;

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
        $user = $this->user();
        
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

    public function options()
    {
        /*
            Relations
        */
        $withs = [];

        /*
            Conditions
        */
        $wheres = [];

        /*
            Relation Conditions
        */
        $whereHases = [];
        $whereHases['owner'] = [
            [
                'column' => 'user_id',
                'value' => $this->user()->id,
            ]
        ];

        return [
            'withs' => $withs,
            'wheres' => $wheres,
            'where_hases' => $whereHases,
        ];
    }
}
