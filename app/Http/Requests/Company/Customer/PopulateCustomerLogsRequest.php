<?php

namespace App\Http\Requests\Company\Customer;

use App\Models\Customer\Customer;
use App\Models\Log\Log;
use App\Traits\CompanyPopulateRequestOptions;
use App\Traits\RequestHasRelations;
use DB;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @TODO Refactor and build unit test for each query cases.
 * @TODO Ensure only show log from the latest to oldest
 */
class PopulateCustomerLogsRequest extends FormRequest
{
    use CompanyPopulateRequestOptions, RequestHasRelations;

    protected array $relationNames = [
        "causer" => true,
        "subject" => true
    ];
    /**
     * Customer object
     *
     * @var Customer|null
     */
    private ?Customer $customer = null;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()
            ->fresh()
            ->can('view-any-log');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            "force" => "nullable|boolean",
            "order_by" => ["nullable", Rule::in("ASC", "DESC")],
            "log_name" => "nullable|string",
            "log_name_contains" => "nullable|string",
            "log_name_ins" => "nullable",
            "subject_type" => "nullable|string",
            "subject_type_contains" => "nullable|string",
            "subject_type_ins" => "nullable",
            "page" => "nullable|numeric|digits_between:1,99999",
            "date" => "nullable|date",
            "start" => "nullable|date",
            "end" => "nullable|date",
        ];
    }

    /**
     * Collect the company options
     *
     * @return array
     */
    public function companyOptions(): array
    {
        $per_page = $this->options()["per_page"];
        return array_merge($this->collectCompanyOptions(), compact("per_page"));
    }

    /**
     * Collect the company options
     *
     * @return array
     */
    public function options(): array
    {
        $customer = $this->getCustomer();

        foreach ($this->getRelations() as $relationName) {
            $this->addWith($relationName);
        }

        $this->addWhere([
            "column" => "subject_type",
            "value" => "App\Models\Customer\Customer"
        ]);

        $this->addWhere([
            "column" => "subject_id",
            "value" => $customer->id
        ]);

        $orderBy = $this->get('order_by') ? $this->get('order_by') : 'DESC';


        switch (true) {
            case $this->has("start") && $this->has("end"):
                $this->addWhere([
                    "column" => DB::raw("DATE(created_at)"),
                    "operator" => ">=",
                    "value" => $this->get("start")
                ]);
                $this->addWhere([
                    "column" => DB::raw("DATE(created_at)"),
                    "operator" => "<=",
                    "value" => $this->get("end")
                ]);
                break;
            case $this->has("date"):
                $this->addWhere([
                    "column" => DB::raw("DATE(created_at)"),
                    "value" => $this->get("date")
                ]);
                break;
            default:

                $this->addWhere([
                    "column" => DB::raw("DATE(created_at)"),
                    "value" => function ($query) use ($orderBy) {
                        return $query->select(DB::raw("DATE(created_at)"))
                            ->from((new Log)->getTable())
                            ->where("company_id", $this->user()->company->id)
                            ->orderBy("created_at", $orderBy)
                            ->distinct('created_at')
                            ->offset($this->get("page") - 1)
                            ->limit(1);
                    }
                ]);
        }

        $this->addOrderBy("created_at", $orderBy);

        return array_merge($this->collectOptions(), ["per_page" => 1000]);
    }

    /**
     * Get Customer based on supplied input
     *
     * @return Customer
     */
    public function getCustomer(): Customer
    {
        if ($this->customer) {
            return $this->customer;
        }
        $id = $this->input('id') ?: $this->input('customer_id');

        return $this->customer = Customer::findOrFail($id);
    }

    /**
     * Prepare input requests before validation
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        $force = $this->input('force');
        $this->merge(['force' => strtobool($force)]);

        $this->merge([
            'page' => intval($this->get("page")) ?: 1
        ]);

        if ($this->has("date")) {
            $this->merge(['date' => carbon()->parse($this->get("date"))]);
        }
        if ($this->has("start")) {
            $this->merge(['start' => carbon()->parse($this->get("start"))]);
        }
        if ($this->has("end")) {
            $this->merge(['end' => carbon()->parse($this->get("end"))]);
        }
    }
}
