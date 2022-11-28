<?php

namespace App\Http\Requests\Company\Invoices;

use App\Enums\Invoice\InvoiceStatus;
use App\Traits\CompanyPopulateRequestOptions;
use App\Traits\RequestHasRelations;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PopulateCompanyInvoicesRequest extends FormRequest
{
    use RequestHasRelations;
    use CompanyPopulateRequestOptions;

    /**
     * List of configurable relationships
     *
     * @var array
     */
    protected array $relationNames = [
        'with_customer' => true,
        'with_items.workService' => false,
    ];

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()
            ->fresh()
            ->can('view-any-invoice');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'start' => ['datetime'],
            'end' => ['datetime'],
            'status' => ['nullable', Rule::in(InvoiceStatus::getValues())]
        ];
    }

    /**
     * Collect options for the queries in repository
     *
     * @return array
     */
    public function options(): array
    {
        if ($this->has('status')) {
            $status = $this->input('status');
            $this->addWhere([
                'column' => 'status',
                'operator' => '=',
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

        $this->addOrderBy('created_at');

        $this->setWiths($this->getRelations());

        return $this->collectCompanyOptions();
    }

    /**
     * Prepare input before validation
     *
     * This is for loading the needed relationships of the results
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        $this->prepareRelationInputs();
    }
}
