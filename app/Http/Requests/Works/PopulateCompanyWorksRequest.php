<?php

namespace App\Http\Requests\Works;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Traits\CompanyPopulateRequestOptions;

class PopulateCompanyWorksRequest extends FormRequest
{
    use CompanyPopulateRequestOptions;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('view-any-work');
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
        if ($this->input('appointment_id')) {
            $this->addWhereHas('appointments', [
                [
                    'column' => 'appointments.id',
                    'value' => $this->input('appointment_id'),
                ]
            ]);
        }

        if ($this->input('quotation_id')) {
            $this->addWhereHas('quotations', [
                [
                    'column' => 'quotations.id',
                    'value' => $this->input('quotation_id'),
                ]
            ]);
        }

        if ($this->input('work_contract_id')) {
            $this->addWhere([
                'column' => 'work_contract_id',
                'value' => $this->input('work_contract_id'),
            ]);
        }

        if ($status = $this->input('status')) {
            $this->addWhere([
                'column' => 'status',
                'value' => $status,
            ]);
        }

        if ($createdFrom = $this->input('created_from')) {
            $createdFrom = carbon()->parse($createdFrom)
                ->startOfDay()
                ->toDateTimeString();
            $this->addWhere([
                'column' => 'created_at',
                'operator' => '>=',
                'value' => $createdFrom,
            ]);
        }

        if ($createdTo = $this->input('created_to')) {
            $createdTo = carbon()->parse($createdTo)
                ->endOfDay()
                ->toDateTimeString();
            $this->addWhere([
                'column' => 'created_at',
                'operator' => '<=',
                'value' => $createdTo,
            ]);
        }

        if ($lastUpdatedFrom = $this->input('last_updated_from')) {
            $lastUpdatedFrom = carbon()->parse($lastUpdatedFrom)
                ->startOfDay()
                ->toDateTimeString();
            $this->addWhere([
                'column' => 'updated_at',
                'operator' => '>=',
                'value' => $lastUpdatedFrom,
            ]);
        }

        if ($lastUpdatedTo = $this->input('last_updated_to')) {
            $lastUpdatedTo = carbon()->parse($lastUpdatedTo)
                ->endOfDay()
                ->toDateTimeString();
            $this->addWhere([
                'column' => 'updated_at',
                'operator' => '<=',
                'value' => $lastUpdatedTo,
            ]);
        }

        if ($executedFrom = $this->input('executed_from')) {
            $executedFrom = carbon()->parse($executedFrom)
                ->startOfDay()
                ->toDateTimeString();
            $this->addWhere([
                'column' => 'executed_from',
                'operator' => '>=',
                'value' => $executedFrom,
            ]);
        }

        if ($executedTo = $this->input('executed_to')) {
            $executedTo = carbon()->parse($executedTo)
                ->endOfDay()
                ->toDateTimeString();
            $this->addWhere([
                'column' => 'executed_to',
                'operator' => '<=',
                'value' => $executedTo,
            ]);
        }

        if ($finishedFrom = $this->input('finished_from')) {
            $finishedFrom = carbon()->parse($finishedFrom)
                ->startOfDay()
                ->toDateTimeString();
            $this->addWhere([
                'column' => 'finished_at',
                'operator' => '>=',
                'value' => $finishedFrom,
            ]);
        }

        if ($finishedTo = $this->input('finished_to')) {
            $finishedTo = carbon()->parse($finishedTo)
                ->endOfDay()
                ->toDateTimeString();
            $this->addWhere([
                'column' => 'finished_at',
                'operator' => '<=',
                'value' => $finishedTo,
            ]);
        }

        if ($unitPriceFrom = $this->input('unit_price_from')) {
            $this->addWhere([
                'column' => 'unit_price',
                'operator' => '>=',
                'value' => $unitPriceFrom,
            ]);
        }

        if ($unitPriceTo = $this->input('unit_price_to')) {
            $this->addWhere([
                'column' => 'unit_price',
                'operator' => '<=',
                'value' => $unitPriceTo,
            ]);
        }

        if ($totalPriceFrom = $this->input('total_price_from')) {
            $this->addWhere([
                'column' => 'total_price',
                'operator' => '>=',
                'value' => $totalPriceFrom,
            ]);
        }

        if ($totalPriceTo = $this->input('total_price_to')) {
            $this->addWhere([
                'column' => 'total_price',
                'operator' => '<=',
                'value' => $totalPriceTo,
            ]);
        }

        if ($includeTaxOnly = $this->input('include_tax_only')) {
            $includeTaxOnly = strtobool($includeTaxOnly);
            $this->addWhere([
                'column' => 'include_tax',
                'value' => $includeTaxOnly,
            ]);
        }

        if ($withQuotation = $this->input('with_quotation')) {
            $this->addWith('quotation');
        }

        if ($withExecutionsCount = $this->input('with_executions_count')) {
            $this->addWithCount('executeWorks');
        }

        return $this->collectCompanyOptions();
    }
}
