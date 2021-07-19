<?php

namespace App\Http\Requests\Invoices;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Traits\CompanyPopulateRequestOptions;

use App\Rules\HasCompanyPermission;

class PopulateCompanyInvoicesRequest extends FormRequest
{
    use CompanyPopulateRequestOptions;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('view-any-invoice');
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

        if ($status = $this->get('status')) {
            $this->addWhere([
                'column' => 'status',
                'operator' => '=',
                'value' => $status,
            ]);
        }

        $this->setWiths(['referenceable']);

        return $this->collectCompanyOptions();
    }
}
