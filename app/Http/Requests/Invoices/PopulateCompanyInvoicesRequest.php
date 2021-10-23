<?php

namespace App\Http\Requests\Invoices;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Traits\CompanyPopulateRequestOptions;

use App\Traits\RequestHasRelations;
use App\Rules\HasCompanyPermission;

class PopulateCompanyInvoicesRequest extends FormRequest
{
    use RequestHasRelations;
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

    protected function prepareForValidation()
    {
        $this->prepareRelationInputs();
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
        if ($this->has('start')) {
            $start = $this->input('start');
            $this->addWhere([
                'column' => 'created_at',
                'operator' => '>=',
                'value' => $start,
            ]);
        }

        if ($this->has('end')) {
            $end = $this->input('end');
            $this->addWhere([
                'column' => 'created_at',
                'operator' => '<=',
                'value' => $end,
            ]);
        }

        if ($this->has('status')) {
            $status = $this->input('status');
            $this->addWhere([
                'column' => 'status',
                'operator' => '=',
                'value' => $status,
            ]);
        }

        $this->addOrderBy('created_at', 'DESC');

        return $this->collectCompanyOptions();
    }
}
