<?php

namespace App\Http\Requests\Costs;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Traits\RequestHasRelations;
use App\Traits\CompanyPopulateRequestOptions;

class PopulateCompanyCostsRequest extends FormRequest
{
    use RequestHasRelations;
    use CompanyPopulateRequestOptions;

    private $relationNames = [
        'with_company' => false,
        'with_costables' => true,
        'with_receipt' => false,
    ];

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('view-any-cost');
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

    /**
     * Get populate query and settings
     *
     * @return array
     */
    public function options()
    {
        if ($fromDate = $this->input('from_date')) {
            $fromDateTime = carbon()->parse($fromDate)
                ->startOfDay()
                ->toDateTimeString();
            $this->addWhere([
                'column' => 'created_at',
                'operator' => '>=',
                'value' => $fromDateTime
            ]);
        }

        if ($toDate = $this->input('to_date')) {
            $toDateTime = carbon()->parse($toDate)
                ->endOfDay()
                ->toDateTimeString();
            $this->addWhere([
                'column' => 'created_at',
                'operator' => '<=',
                'value' => $toDateTime,
            ]);
        }

        if ($fromAmount = $this->input('from_amount')) {
            $this->addWhere([
                'column' => 'amount',
                'operator' => '>=',
                'value' => $fromAmount,
            ]);
        }

        if ($toAmount = $this->input('to_amount')) {
            $this->addWhere([
                'column' => 'amount',
                'operator' => '<=',
                'value' => $toAmount,
            ]);
        }

        if ($fromPaidAmount = $this->input('from_paid_amount')) {
            $this->addWhere([
                'column' => 'paid_amount',
                'operator' => '>=',
                'value' => $fromPaidAmount,
            ]);
        }

        if ($toPaidAmount = $this->input('to_paid_amount')) {
            $this->addWhere([
                'column' => 'paid_amount',
                'operator' => '<=',
                'value' => $toPaidAmount,
            ]);
        }

        if ($orderByAmount = $this->input('order_by_amount')) {
            $this->addOrderBy('amount', $orderByAmount);
        }

        if ($orderByPaidAmount = $this->input('order_by_paid_amount')) {
            $this->addOrderBy('paid_amount', $orderByPaidAmount);
        }

        if ($relations = $this->relations()) {
            $this->setWiths($relations);

            if ($this->relationNames['with_costables'] === true) {
                $this->addWith('costables.costable');
            }
        }

        return $this->collectCompanyOptions();
    }
}
