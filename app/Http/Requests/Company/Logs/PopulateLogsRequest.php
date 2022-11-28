<?php

namespace App\Http\Requests\Company\Logs;

use App\Models\Log\Log;
use App\Traits\CompanyPopulateRequestOptions;
use App\Traits\RequestHasRelations;
use DB;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PopulateLogsRequest extends FormRequest
{
    use CompanyPopulateRequestOptions;
    use RequestHasRelations;

    protected array $relationNames = [
        "causer" => true,
        "subject" => true,
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
            ->can("view-any-log");
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "force" => "nullable|boolean",
            "order_by" => ["nullable", Rule::in("ASC", "DESC")],
            "log_name" => "nullable|string",
            "log_name_contains" => "nullable|string",
            "log_name_ins" => "nullable",
            "subject_id" => "nullable|string",
            "subject_ids" => "nullable|string",
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
        foreach ($this->getRelations() as $relationName) {
            $this->addWith($relationName);
        }

        if ($this->has("log_name")) {
            $this->addWhere([
                "column" => "log_name",
                "value" => $this->get("log_name"),
            ]);
        }
        if ($this->has("log_name_contains")) {
            $this->addWhere([
                "column" => "log_name",
                "operator" => "LIKE",
                "value" => "%" . $this->get("log_name_contains") . "%",
            ]);
        }
        if ($this->has("log_name_ins")) {
            $this->addWhereIn([
                "column" => "log_name",
                "values" => $this->get("log_name_ins"),
            ]);
        }
        if ($this->has("subject_id")) {
            $this->addWhere([
                "column" => "subject_id",
                "value" => $this->get("subject_id"),
            ]);
        }
        if ($this->has("subject_ids")) {
            $this->addWhere([
                "column" => "subject_id",
                "value" => $this->get("subject_ids"),
            ]);
        }
        if ($this->has("subject_type")) {
            $this->addWhere([
                "column" => "subject_type",
                "value" => $this->get("subject_type"),
            ]);
        }
        if ($this->has("subject_type_contains")) {
            $this->addWhere([
                "column" => "subject_type",
                "operator" => "LIKE",
                "value" => "%" . $this->get("subject_type_contains") . "%",
            ]);
        }
        if ($this->has("subject_type_ins")) {
            $this->addWhereIn([
                "column" => "subject_type",
                "values" => $this->get("subject_type_ins"),
            ]);
        }

        switch (true) {
            case $this->has("start") && $this->has("end"):
                $this->addWhere([
                    "column" => DB::raw("DATE(created_at)"),
                    "operator" => ">=",
                    "value" => $this->get("start"),
                ]);
                $this->addWhere([
                    "column" => DB::raw("DATE(created_at)"),
                    "operator" => "<=",
                    "value" => $this->get("end"),
                ]);
                break;
            case $this->has("date"):
                $this->addWhere([
                    "column" => DB::raw("DATE(created_at)"),
                    "value" => $this->get("date"),
                ]);
                break;
            default:
                $this->addWhere([
                    "column" => DB::raw("DATE(created_at)"),
                    "value" => function ($query) {
                        return $query
                            ->select(DB::raw("DATE(created_at)"))
                            ->from((new Log())->getTable())
                            ->where("company_id", $this->user()->company->id)
                            ->orderBy("created_at", $this->get("order_by"))
                            ->distinct("created_at")
                            ->offset($this->get("page") - 1)
                            ->limit(1);
                    },
                ]);
        }

        $this->addOrderBy("created_at", $this->get("order_by"));

        return array_merge($this->collectOptions(), ["per_page" => 1000]);
    }

    /**
     * Prepare input requests before validation
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        $force = $this->input("force");
        $this->merge(["force" => strtobool($force)]);

        $this->merge([
            "page" => intval($this->get("page")) ?: 1,
        ]);
        if ($this->has("subject_ids")) {
            $this->merge([
                "subject_ids" => explode(",", $this->get("subject_ids")),
            ]);
        }
        if ($this->has("subject_type_ins")) {
            $this->merge([
                "subject_type_ins" => array_map(
                    fn ($subjectType) => customNamespace(
                        "App\\Models\\",
                        $subjectType
                    ),
                    explode(",", $this->get("subject_type_ins"))
                ),
            ]);
        }
        if ($this->has("log_name_ins")) {
            $this->merge([
                "log_name_ins" => explode(",", $this->get("log_name_ins")),
            ]);
        }

        if ($this->has("date")) {
            $this->merge(["date" => carbon()->parse($this->get("date"))]);
        }
        if ($this->has("start")) {
            $this->merge(["start" => carbon()->parse($this->get("start"))]);
        }
        if ($this->has("end")) {
            $this->merge(["end" => carbon()->parse($this->get("end"))]);
        }
    }
}
