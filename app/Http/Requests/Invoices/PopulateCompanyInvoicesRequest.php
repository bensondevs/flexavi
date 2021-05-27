<?php

namespace App\Http\Requests\Invoices;

use Illuminate\Foundation\Http\FormRequest;

use App\Traits\PopulateCompanyRequestOptions;

use App\Rules\HasCompanyPermission;

class PopulateCompanyInvoicesRequest extends FormRequest
{
    use PopulateCompanyRequestOptions;

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
            'start' => ['datetime'],
            'end' => 'datetime',
        ];
    }

    public function options()
    {
        if ($start = $this->get('start')) {
            $this->addWhere([
                'column' => 'created_at',
                'operator' => '>=',
                'value' => $start,
            ]);
        }

        if ($end = $this->get('end')) {
            $this->addWhere([
                'column' => 'created_at',
                'operator' => '<=',
                'value' => $end,
            ]);
        }

        // $this->setWiths(['workContract', 'items', 'paymentTerms']);

        return $this->collectCompanyOptions();
    }
}
