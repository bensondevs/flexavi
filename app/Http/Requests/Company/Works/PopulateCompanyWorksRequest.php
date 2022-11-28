<?php

namespace App\Http\Requests\Company\Works;

use App\Traits\CompanyPopulateRequestOptions;
use Illuminate\Foundation\Http\FormRequest;

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
        return $this->user()
            ->fresh()
            ->can('view-any-work');
    }

    /**
     * Get options
     *
     * @return array
     */
    public function options()
    {
        if ($status = $this->input('status')) {
            $this->addWhere([
                'column' => 'status',
                'value' => $status,
            ]);
        }
        if ($createdAfter = $this->input('created_after')) {
            $createdAfter = carbon()
                ->parse($createdAfter)
                ->startOfDay()
                ->toDateTimeString();
            $this->addWhere([
                'column' => 'created_at',
                'operator' => '>=',
                'value' => $createdAfter,
            ]);
        }
        if ($createdBefore = $this->input('created_before')) {
            $createdBefore = carbon()
                ->parse($createdBefore)
                ->endOfDay()
                ->toDateTimeString();
            $this->addWhere([
                'column' => 'created_at',
                'operator' => '<=',
                'value' => $createdBefore,
            ]);
        }
        if ($lastUpdatedBefore = $this->input('last_updated_before')) {
            $lastUpdatedBefore = carbon()
                ->parse($lastUpdatedBefore)
                ->startOfDay()
                ->toDateTimeString();
            $this->addWhere([
                'column' => 'updated_at',
                'operator' => '>=',
                'value' => $lastUpdatedBefore,
            ]);
        }
        if ($lastUpdatedAfter = $this->input('last_updated_after')) {
            $lastUpdatedAfter = carbon()
                ->parse($lastUpdatedAfter)
                ->endOfDay()
                ->toDateTimeString();
            $this->addWhere([
                'column' => 'updated_at',
                'operator' => '<=',
                'value' => $lastUpdatedAfter,
            ]);
        }
        if ($executedAfter = $this->input('executed_after')) {
            $executedAfter = carbon()
                ->parse($executedAfter)
                ->startOfDay()
                ->toDateTimeString();
            $this->addWhere([
                'column' => 'executed_at',
                'operator' => '>=',
                'value' => $executedAfter,
            ]);
        }
        if ($executedBefore = $this->input('executed_before')) {
            $executedBefore = carbon()
                ->parse($executedBefore)
                ->endOfDay()
                ->toDateTimeString();
            $this->addWhere([
                'column' => 'executed_at',
                'operator' => '<=',
                'value' => $executedBefore,
            ]);
        }
        if ($finishedAfter = $this->input('finished_after')) {
            $finishedAfter = carbon()
                ->parse($finishedAfter)
                ->startOfDay()
                ->toDateTimeString();
            $this->addWhere([
                'column' => 'finished_at',
                'operator' => '>=',
                'value' => $finishedAfter,
            ]);
        }
        if ($finishedBefore = $this->input('finished_before')) {
            $finishedBefore = carbon()
                ->parse($finishedBefore)
                ->endOfDay()
                ->toDateTimeString();
            $this->addWhere([
                'column' => 'finished_at',
                'operator' => '<=',
                'value' => $finishedBefore,
            ]);
        }
        if ($minQuantity = $this->input('min_quantity')) {
            $this->addWhere([
                'column' => 'quantity',
                'operator' => '>=',
                'value' => $minQuantity,
            ]);
        }
        if ($maxQuantity = $this->input('max_quantity')) {
            $this->addWhere([
                'column' => 'quantity',
                'operator' => '<=',
                'value' => $maxQuantity,
            ]);
        }
        if ($minUnitPrice = $this->input('min_unit_price')) {
            $this->addWhere([
                'column' => 'unit_price',
                'operator' => '>=',
                'value' => $minUnitPrice,
            ]);
        }
        if ($maxUnitPrice = $this->input('max_unit_price')) {
            $this->addWhere([
                'column' => 'unit_price',
                'operator' => '<=',
                'value' => $maxUnitPrice,
            ]);
        }
        if ($minTotalPrice = $this->input('min_total_price')) {
            $this->addWhere([
                'column' => 'total_price',
                'operator' => '>=',
                'value' => $minTotalPrice,
            ]);
        }
        if ($maxTotalPrice = $this->input('max_total_price')) {
            $this->addWhere([
                'column' => 'total_price',
                'operator' => '<=',
                'value' => $maxTotalPrice,
            ]);
        }
        if ($this->input('with_quotation')) {
            $this->addWith('quotation');
        }
        if ($this->input('with_executions_count')) {
            $this->addWithCount('executeWorks');
        }
        if ($orderByQuantity = $this->input('order_by_quantity')) {
            $this->addOrderBy('quantity', $orderByQuantity);
        }
        if ($orderByUnitPrice = $this->input('order_by_unit_price')) {
            $this->addOrderBy('unit_price', $orderByUnitPrice);
        }
        if ($orderByTotalPrice = $this->input('order_by_total_price')) {
            $this->addOrderBy('total_price', $orderByTotalPrice);
        }

        return $this->collectCompanyOptions();
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

    /**
     * Manipulate received input to be validated.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        if ($this->has('status')) {
            $status = $this->input('status');
            $status = is_numeric($status) ? $status : ((int)$status);
            $this->merge(['status' => $status]);
        }
        if ($this->has('min_quantity')) {
            $minQuantity = $this->input('min_quantity');
            $minQuantity = is_numeric($minQuantity)
                ? $minQuantity
                : ((int)$minQuantity);
            $this->merge(['min_quantity' => $minQuantity]);
        }
        if ($this->has('max_quantity')) {
            $maxQuantity = $this->input('max_quantity');
            $maxQuantity = is_numeric($maxQuantity)
                ? $maxQuantity
                : ((int)$maxQuantity);
            $this->merge(['max_quantity' => $maxQuantity]);
        }
        if ($this->has('min_unit_price')) {
            $minUnitPrice = $this->input('min_unit_price');
            $minUnitPrice = is_numeric($minUnitPrice)
                ? $minUnitPrice
                : ((float)$minUnitPrice);
            $this->merge(['min_unit_price' => $minUnitPrice]);
        }
        if ($this->has('max_unit_price')) {
            $maxUnitPrice = $this->input('max_unit_price');
            $maxUnitPrice = is_numeric($maxUnitPrice)
                ? $maxUnitPrice
                : ((float)$maxUnitPrice);
            $this->merge(['max_unit_price' => $maxUnitPrice]);
        }
        if ($this->has('min_total_price')) {
            $minTotalPrice = $this->input('min_total_price');
            $minTotalPrice = is_numeric($minTotalPrice)
                ? $minTotalPrice
                : ((float)$minTotalPrice);
            $this->merge(['min_total_price' => $minTotalPrice]);
        }
        if ($this->has('max_total_price')) {
            $maxTotalPrice = $this->input('max_total_price');
            $maxTotalPrice = is_numeric($maxTotalPrice)
                ? $maxTotalPrice
                : ((float)$maxTotalPrice);
            $this->merge(['max_total_price' => $maxTotalPrice]);
        }
    }
}
