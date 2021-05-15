<?php

namespace App\Http\Requests\Inspectors;

use Illuminate\Foundation\Http\FormRequest;

class PopulateInspectorsRequest extends FormRequest
{
    private $withs = [];
    private $wheres = [];
    private $whereHases = [];

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
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
        $wheres = [
            [
                'column' => 'company_id',
                'value' => $this->get('company_id'),
            ]
        ];
    }
}
