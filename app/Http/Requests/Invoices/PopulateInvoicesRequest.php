<?php

namespace App\Http\Requests\Invoices;

use Illuminate\Foundation\Http\FormRequest;

use App\Rules\HasCompanyPermission;

class PopulateInvoicesRequest extends FormRequest
{
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
        $rules = [];
        $rules['company_id'] = ['required', 'string', new HasCompanyPermission];
        $rules['start'] = ['date_format:Y-m-d H:i:s'];
        $rules['end'] = ['date_format:Y-m-d H:i:s'];

        return $rules;
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
        array_push($wheres, [
            'column' => 'company_id',
            'value' => $this->get('company_id'),
        ]);
        if ($start = $this->get('start'))
            array_push($wheres, [
                'column' => 'created_at',
                'value' => $start,
            ]);
        if ($end = $this->get('end'))
            array_push($wheres, [
                'column' => 'created_at',
                'value' => $end,
            ]);


        /*
            Relation Condition
        */
        $whereHases = [];

        return [
            'withs' => $withs,
            'wheres' => $wheres,
            'where_hases' = $whereHases,
        ];
    }
}
