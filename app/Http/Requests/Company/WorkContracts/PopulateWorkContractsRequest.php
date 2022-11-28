<?php

namespace App\Http\Requests\Company\WorkContracts;

use App\Enums\WorkContract\WorkContractStatus;
use App\Traits\CompanyPopulateRequestOptions;
use App\Traits\RequestHasRelations;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PopulateWorkContractsRequest extends FormRequest
{
    use CompanyPopulateRequestOptions;
    use RequestHasRelations;

    /**
     *  List of loadable relations
     *
     * @var array
     */
    private array $relationNames = [
        "with_customer" => false
    ];

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()->fresh()->can("view-any-work-contract");
    }

    /**
     * Get options
     *
     * @return array
     */
    public function options(): array
    {
        foreach ($this->getRelations() as $relationName) {
            $this->addWith($relationName);
        }

        if ($status = $this->input('status')) {
            $this->addWhere([
                'column' => 'status',
                'value' => $status,
            ]);
        }

        if ($this->has('customer_id')) {
            $this->addWhere([
                'column' => 'customer_id',
                'operator' => '=',
                'value' => $this->input('customer_id'),
            ]);
        }

        return $this->collectCompanyOptions();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            "status" => ['nullable', Rule::in(WorkContractStatus::getValues())],
        ];
    }
}
