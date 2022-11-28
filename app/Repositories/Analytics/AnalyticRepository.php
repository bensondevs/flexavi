<?php

namespace App\Repositories\Analytics;

// Repostitories
use App\Enums\Analytic\AnalyticType;
use App\Enums\Appointment\AppointmentStatus;
use App\Enums\Appointment\AppointmentType;
use App\Enums\Employee\EmployeeType;
use App\Enums\Invoice\InvoiceStatus;
use App\Enums\Warranty\WarrantyStatus;
use App\Enums\Work\WorkStatus;
use App\Enums\Workday\WorkdayStatus;
use App\Enums\Worklist\WorklistStatus;
use App\Enums\WorkService\WorkServiceStatus;
use App\Models\{Analytic\Analytic,
    Appointment\Appointment,
    Company\Company,
    Cost\Cost,
    Customer\Customer,
    Employee\Employee,
    Invoice\Invoice,
    Revenue\Revenue,
    Warranty\Warranty,
    Work\Work,
    Workday\Workday,
    Worklist\Worklist,
    WorkService\WorkService};
use App\Repositories\Base\BaseRepository;
use App\Traits\{DateRangeGuesser, GroupByGuesser};
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Support\Facades\DB;

// Enums
// Models

class AnalyticRepository extends BaseRepository
{
    use DateRangeGuesser;
    use GroupByGuesser;

    /**
     *  The company model property
     *
     *  @var Company|null
     */
    private $company;

    /**
     *  The analytictype property
     *
     *  @var int|null
     */
    private $type;

    /**
     *  determine to recalculate analysis result or not
     *
     *  @var bool|null
     */
    private $recalculate;

    /**
     * Repository constructor method
     *
     * @return void
     */
    public function __construct()
    {
        //$this->chartModel = new ChartJsModel();
        $this->setInitModel(new Analytic());
    }

    /**
     * Set Chart Model to Chart JS
     *
     * @return void
     */
    /*public function useChartJs()
    {
        $this->chartModel = new ChartJsModel();
    }/*

    /**
     * Set Chart Model to Apex Chart
     *
     * @return void
     */
    /*public function useApexChart()
    {
        $this->chartModel = new ApexChartModel();
    }*/

    /**
     * Set the company
     *
     * @param Company $company
     * @return static
     */
    public function setCompany(Company $company)
    {
        $this->company = $company;
        return $this;
    }

    /**
     * Get the company
     *
     * @return Company|null
     */
    public function getCompany()
    {
        return $this->company = is_null($this->company) ? auth()->user()->company : $this->company;
    }

    /**
     * Determine whether to recalculate the result or not
     *
     * @param bool  $recalculate
     * @return static
     */
    public function recalculate(bool $recalculate)
    {
        $this->recalculate = $recalculate;
        return $this;
    }

    /**
     * Set analytic type
     *
     * @param int  $type
     * @return static
     */
    public function setType(int $type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * Get analytic type
     *
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Get Analytyc model based on anlytic type, company, and date
     *
     * @return Analytic|null
     */
    public function getModel()
    {
        $model = parent::getModel();
        if (
            $model && count( // if model is set and the model has attributes not only id return model
                array_filter(
                    $model->toArray(),
                    fn ($val, $key) => $key != "id",
                    ARRAY_FILTER_USE_BOTH
                )
            )
        ) {
            return $model;
        }
        // otherwise do the database query to get model

        $model = Analytic::where('company_id', $this->getCompany()->id)
            ->where("analytic_type", $this->type)
            ->where('start', $this->start)
            ->where('end', $this->end)
            ->first();

        $this->setModel($model);

        return $model;
    }

    /**
     * Save analysisResult to database
     *
     * @param array  $analysisResult
     * @return \App\Models\Analytic\Analytic
     */
    private function saveAnalysisResult(array $analysisResult)
    {
        $start = ($this->start instanceof Carbon) ? $this->start->toDateString() : $this->start;
        $end = ($this->end instanceof Carbon) ? $this->end->toDateString() : $this->end;

        $analyticData =  [
            'company_id' => $this->getCompany()->id,
            'analytic_type' => $this->type,
            'start' => $start,
            'end' => $end
        ];
        $analytic = Analytic::where($analyticData);

        if ($analytic->exists()) {
            $analytic = $analytic->first();
            $analytic->analysis_result = $analysisResult;
            $analytic->save();
            $this->setModel($analytic);
        } else {
            $analytic = Analytic::create(
                array_merge(
                    $analyticData,
                    [
                        'analysis_result' => $analysisResult
                    ]
                )
            );
            $this->setModel($analytic);
        }

        return $analytic;
    }

    /**
     * * DEFINE YOUR OWN ANALYTIC STUFF BELOW HERE
     * * DEFINE YOUR OWN ANALYTIC STUFF BELOW HERE
     * * DEFINE YOUR OWN ANALYTIC STUFF BELOW HERE
     * * DEFINE YOUR OWN ANALYTIC STUFF BELOW HERE
     * * DEFINE YOUR OWN ANALYTIC STUFF BELOW HERE
     * * DEFINE YOUR OWN ANALYTIC STUFF BELOW HERE
     * * DEFINE YOUR OWN ANALYTIC STUFF BELOW HERE
     * * DEFINE YOUR OWN ANALYTIC STUFF BELOW HERE
     * * DEFINE YOUR OWN ANALYTIC STUFF BELOW HERE
     * * DEFINE YOUR OWN ANALYTIC STUFF BELOW HERE
     * * DEFINE YOUR OWN ANALYTIC STUFF BELOW HERE
     * * DEFINE YOUR OWN ANALYTIC STUFF BELOW HERE
     */

    /**
     * Generate result graph
     *
     * @return array
     */
    public function result()
    {
        $companyID = $this->getCompany()->id;
        $analytic = $this->setType(AnalyticType::Result)->getModel();

        if (!$analytic || $this->recalculate) {
            $results = Revenue::select([
                \DB::raw(
                    \Str::replaceFirst(
                        "created_at",
                        "revenues.created_at",
                        $this->getGroupByQuery() instanceof \Illuminate\Database\Query\Expression ?
                            $this->getGroupByQuery()->getValue() : "revenues.created_at"
                    )
                ),
                \DB::raw("SUM(revenues.amount) as revenue_sum"),
                \DB::raw("SUM(costs.amount) as cost_sum"),
                \DB::raw("(SUM(revenues.amount) - SUM(costs.amount)) as profit_sum"),
            ])->join(
                "costs",
                fn ($join) => $join
                    ->when(
                        $this->getGroupByAliasKey() == "hour",
                        fn ($q) => $q->on(
                            \DB::raw("DATE_FORMAT(costs.created_at, '%Y-%m-%d %H:00:00.000')"),
                            "=",
                            \DB::raw("DATE_FORMAT(revenues.created_at, '%Y-%m-%d %H:00:00.000')")
                        )
                    )
                    ->when(
                        $this->getGroupByAliasKey() == "date",
                        fn ($q) => $q->on(\DB::raw("DATE(costs.created_at)"), "=", \DB::raw("DATE(revenues.created_at)"))
                    )
                    ->when(
                        $this->getGroupByAliasKey() == "month",
                        fn ($q) => $q->on(\DB::raw("MONTH(costs.created_at)"), "=", \DB::raw("MONTH(revenues.created_at)"))
                    )
                    ->when(
                        $this->getGroupByAliasKey() == "year",
                        fn ($q) => $q->on(\DB::raw("YEAR(costs.created_at)"), "=", \DB::raw("YEAR(revenues.created_at)"))
                    )
                    ->where("costs.company_id", $companyID)
            )
                ->where("revenues.company_id", $companyID)
                ->when(
                    $this->start && $this->end,
                    fn ($q) => $q->whereBetween("revenues.created_at", [$this->start, $this->end])
                )
                ->groupBy($this->getGroupByAliasKey())
                ->get()
                ->toArray();

            if (empty($results)) {
                return [];
            }

            $key = array_key_first($results[0]);
            $analysisResult = [];
            foreach ($results as $result) {
                $analysisResult[$result[$key]]  = \Arr::except($result, $key);
            }
            $analytic = $this->setType(AnalyticType::Result)->saveAnalysisResult($analysisResult);
        }

        return $analytic->analysis_result;
    }

    /**
     * Analyze revenue and prepare data to be modelled
     *
     * @return array
     */
    public function revenue()
    {
        $companyID = $this->getCompany()->id;
        $analytic = $this->setType(AnalyticType::Revenue)->getModel();

        if (!$analytic || $this->recalculate) {
            $revenues = Revenue::select([
                $this->getGroupByQuery(),
                DB::raw('SUM(amount) as "amount"')
            ])->where("company_id", $companyID)
                ->when($this->start && $this->end, function ($query) {
                    return $query->whereBetween("created_at", [
                        $this->start, $this->end
                    ]);
                })
                ->orderBy('created_at', 'ASC')
                ->groupBy($this->getGroupByAliasKey())
                ->get();

            $analysisResult = [];
            foreach ($revenues as $revenue) {
                $key = $this->getGroupByAliasKey();
                $key = $revenue->{$key};
                $analysisResult[$key] = isset($analysisResult[$key]) ?
                    $analysisResult[$key] +  $revenue->amount
                    : $revenue->amount;
            }

            $analytic = $this->saveAnalysisResult($analysisResult);
        }

        return $analytic->analysis_result;
    }

    /**
     * Analyze revenue and prepare data to be modelled
     *
     * @return array
     */
    public function cost()
    {
        $companyID = $this->getCompany()->id;
        $analytic = $this->setType(AnalyticType::Cost)->getModel();

        if (!$analytic || $this->recalculate) {
            $costs = Cost::select([
                $this->getGroupByQuery(),
                DB::raw('SUM(amount) as "amount"')
            ])->where("company_id", $companyID)
                ->when($this->start && $this->end, function ($query) {
                    return $query->whereBetween("created_at", [
                        $this->start, $this->end
                    ]);
                })
                ->orderBy('created_at', 'ASC')
                ->groupBy($this->getGroupByAliasKey())
                ->get();

            $analysisResult = [];
            foreach ($costs as $cost) {
                $key = $this->getGroupByAliasKey();
                $key = $cost->{$key};
                $analysisResult[$key] = isset($analysisResult[$key]) ?
                    $analysisResult[$key] +  $cost->amount
                    : $cost->amount;
            }

            $analytic = $this->saveAnalysisResult($analysisResult);
        }

        return $analytic->analysis_result;
    }

    /**
     * Generate trend analytic for profits
     *
     * @return Analytic
     */
    public function profit()
    {
        $analytic = $this->setType(AnalyticType::Profit)->getModel();

        if (!$analytic || $this->recalculate) {
            $results = $this->result();
            if (empty($results)) {
                return [];
            }
            $analysisResult =
                array_map(fn ($result) => $result["profit_sum"] ?? $result["profit"], $results);

            $analytic = $this->setType(AnalyticType::Profit)->saveAnalysisResult($analysisResult);
        }

        return $analytic->analysis_result;
    }

    /**
     * Generate Warranties Per Roofer
     *
     * @return array
     */
    public function warrantyPerRoofer()
    {
        $company = $this->getCompany();
        $analytic = $this->setType(AnalyticType::WarrantyPerRoofer)->getModel();

        if (!$analytic || $this->recalculate) {
            $analysisResult = Employee::select("id", "user_id")
                ->with([
                    "user" => fn ($q) => $q->select("users.id", "users.fullname"),
                    "warranties"  =>  fn ($q) => $q->select("warranties.id", "warranties.company_id", "warranties.appointment_id")
                ])
                ->withCount("warranties")
                ->when($this->start && $this->end, function ($query) {
                    return $query->whereHas("warranties", fn ($q) => $q->whereBetween("warranties.created_at", [
                        $this->start, $this->end
                    ]));
                })
                ->where("employees.company_id", $company->id)
                ->where("employees.employee_type", EmployeeType::Roofer)
                ->get()
                ->map(
                    fn (Employee $employee) =>
                    collect($employee)
                        ->merge([
                            "expected_loss" =>
                            $employee->warranties->append("total_company_paid")->sum("total_company_paid")
                        ])->forget("warranties")
                )
                ->sortByDesc("warranties_count")->toArray();

            $analytic = $this->saveAnalysisResult($analysisResult);
        }

        return $analytic->analysis_result;
    }

    /**
     * Generate Customer Shortage
     *
     * @return array
     */
    public function customerShortage()
    {
        $company = $this->getCompany();
        $analytic = $this->setType(AnalyticType::CustomerShortage)->getModel();

        if (!$analytic || $this->recalculate) {
            $needed = 10 ;

            $query = Workday::select("date")
                ->where("company_id", $company->id)
                ->whereHas("appointments", fn ($q) => $q->where("type", AppointmentType::ExecuteWork))
                ->when(
                    $this->start && $this->end,
                    fn ($q) => $q->whereBetween('date', [
                        $this->start, $this->end
                    ])
                )->orderBy("created_at", "DESC");

            /**
             *  Get the available appointment
             */
            $analyticAvailable = $query->withCount([
                "appointments AS appointment_status_available_count"
            ])
                ->withSum(
                    [
                        "appointmentsWorksRevenues AS appointment_status_available_work_revenues_sum_amount" =>
                        fn ($q) => $q
                    ],
                    "amount"
                )
                ->withSum("appointmentsRevenues AS appointment_status_available_revenues_sum_amount", "amount")->get();

            /**
             *  Get the planned appointment
             */
            $analyticPlanned = $query->whereHas("appointments", fn ($q) => $q->whereHas("worklists"))
                ->withCount([
                    "appointments AS appointment_status_planned_count"
                ])
                ->withSum(
                    [
                        "appointmentsWorksRevenues AS appointment_status_planned_work_revenues_sum_amount" =>
                        fn ($q) => $q
                    ],
                    "amount"
                )
                ->withSum("appointmentsRevenues AS appointment_status_planned_revenues_sum_amount", "amount")->get();

            $analysisResult = $analyticAvailable->map(function ($workday, $i) use ($needed, $analyticPlanned) {
                $workday = collect($workday)->merge($analyticPlanned[$i]);

                return $workday->merge([
                    "appointment_status_available_revenues_add_works_revenues_sum_amount"
                    => ($workday["appointment_status_planned_work_revenues_sum_amount"] ?? 0)
                        + ($workday["appointment_status_planned_revenues_sum_amount"] ?? 0),
                    //
                    "appointment_status_planned_revenues_add_works_revenues_sum_amount"
                    => ($workday["appointment_status_available_work_revenues_sum_amount"] ?? 0)
                        + ($workday["appointment_status_available_revenues_sum_amount"] ?? 0),
                    //
                    "needed" => $needed,

                    "appointment_available_shortage_or_overplanned" => ($workday['appointment_status_available_count'] ?? 0) - $needed,
                    "appointment_planned_shortage_or_overplanned" => ($workday['appointment_status_planned_count'] ?? 0) - $needed,
                ]);
            })->toArray();

            $analytic = $this->saveAnalysisResult($analysisResult);
        }
        return $analytic->analysis_result;
    }

    /**
     * Get roofer profit
     *
     * @return array
     */
    public function rooferProfit()
    {
        $company = $this->getCompany();
        $analytic = $this->setType(AnalyticType::RooferProfit)->getModel();

        if (!$analytic || $this->recalculate) {
            $analysisResult = Employee::select(
                "id",
                "user_id",
                "title",
                "employee_type",
                "employment_status",
            )->with(["user" => fn ($q) => $q->select("users.id", "users.fullname")])
                ->withSum([
                    "worklistsRevenues as revenue_sum" =>
                    fn ($q) => $q->whereBetween("revenues.created_at", [$this->start, $this->end])
                ], "amount")
                ->withSum([
                    "worklistsCosts as cost_sum" =>
                    fn ($q) => $q->whereBetween("costs.created_at", [$this->start, $this->end])
                ], "amount")
                ->where("company_id", $company->id)
                ->where("employee_type", EmployeeType::Roofer)
                ->get()->map(function (Employee $employee) {
                    $employee = $employee->append([
                        "employment_status_description", "employee_type_description"
                    ]);
                    return collect($employee)->merge([
                        "profit_sum" => ($employee->revenue_sum ?? 0) - ($employee->cost_sum ?? 0),
                        "fullname" => $employee->user->fullname,
                    ])->except("user");
                })->toArray();

            $analytic = $this->setType(AnalyticType::RooferProfit)->saveAnalysisResult($analysisResult);
            $analytic->refresh();
        }
        return $analytic->analysis_result;
    }

    /**
     * Get the best selling services
     *
     * @return array
     */
    public function bestSellingService()
    {
        $company = $this->getCompany();
        $analytic = $this->setType(AnalyticType::BestSellingService)->getModel();

        $worksCreatedAtBetween = fn (EloquentBuilder $q) => $q->where("status", WorkStatus::Finished)
            ->whereBetween("works.created_at", [$this->start, $this->end]);

        if (!$analytic || $this->recalculate) {
            $analysisResult = WorkService::select([
                "id",
                "company_id",
                "name",
                "price",
                "price as standard_unit_price",
                "unit",
                "status",
            ])->where("company_id", $company->id)
                ->where("status", WorkServiceStatus::Active)
                // Count Order Amount
                ->withCount(["works" => $worksCreatedAtBetween])
                ->withCount(["works AS ordered" => $worksCreatedAtBetween])
                // Average Order Amount
                ->withAvg(["works" => $worksCreatedAtBetween], "quantity")
                ->withAvg(["works AS avg_order_amount" => $worksCreatedAtBetween], "quantity")
                // Average Sales Unit Price
                ->withAvg(["works" => $worksCreatedAtBetween], "unit_price")
                ->withAvg(["works AS avg_sales_unit_price" => $worksCreatedAtBetween], "unit_price")
                // Average Order Value
                ->withAvg(["works" => $worksCreatedAtBetween], "total_price")
                ->withAvg(["works AS avg_order_value" => $worksCreatedAtBetween], "total_price")
                // ####
                ->withSum(["works" => $worksCreatedAtBetween], "quantity")
                ->withSum(["works" => $worksCreatedAtBetween], "unit_price")
                ->withSum(["works" => $worksCreatedAtBetween], "total_price")
                ->orderBy("works_count", "DESC")
                ->get()
                ->map(function (WorkService $workService) {
                    $additionals = ([
                        "difference" => ($workService->avg_sales_unit_price ??  0) - $workService->standard_unit_price,
                        "avg_order_value_difference_percentage" => (($workService->avg_sales_unit_price ?? 0)
                            - $workService->standard_unit_price)
                            / $workService->standard_unit_price
                            * 100
                    ]);
                    return collect($workService)->merge($additionals);
                })
                ->toArray();

            $analytic = $this->setType(AnalyticType::BestSellingService)->saveAnalysisResult($analysisResult);
            $analytic->refresh();
        }
        return $analytic->analysis_result;
    }

    /**
     * Get best selling services per roofer
     *
     * @return array
     */
    public function bestSellingServicePerRoofer()
    {
        $company = $this->getCompany();
        $analytic = $this->setType(AnalyticType::BestSellingServicePerRoofer)->getModel();
        if (!$analytic || $this->recalculate) {
            $analysisResult = Employee::select([
                "employees.id AS employee_id",
                "employees.employee_type AS employee_type",
                "employees.employment_status AS employment_status",

                "users.id AS  user_id",
                "users.fullname AS fullname",

                "works.work_service_id AS service_id",
                "work_services.name AS service_name",
                "work_services.price AS standard_unit_price",
                \DB::raw("COUNT(works.work_service_id) AS service_sold"),
                \DB::raw("AVG(works.unit_price) AS avg_sales_unit_price"),
            ])->where("employees.company_id", $company->id)
                ->where("employees.employee_type", EmployeeType::Roofer)
                ->join("users", "users.id", "=", "employees.user_id")
                ->join("appointment_employees", "appointment_employees.user_id", "=", "users.id")
                ->join("appointments", "appointments.id", "=", "appointment_employees.appointment_id")
                ->join(
                    "workables",
                    fn ($join) => $join->on("workables.workable_id", "=", "appointments.id")
                        ->where("workables.workable_type", "=", Appointment::class)
                )
                ->join(
                    "works",
                    fn ($join) => $join->on("works.id", "=", "workables.work_id")
                        ->whereBetween("works.created_at", [$this->start, $this->end])
                )
                ->join(
                    "work_services",
                    fn ($join) => $join->on("work_services.id", "=", "works.work_service_id")
                )
                ->groupBy("employee_id")
                ->groupBy("work_services.name")
                ->orderByRaw("COUNT(work_services.name) DESC")
                ->get()
                ->groupBy("employee_id")
                ->map(
                    function ($data) {
                        $data = $data->first()
                            ->append(["employment_status_description", "employee_type_description"]);

                        return collect($data)->merge([
                            "difference_percentage" => (($data->avg_sales_unit_price ?? 0)
                                - $data->standard_unit_price)
                                / $data->standard_unit_price
                                * 100
                        ]);
                    }
                )
                ->values()
                ->toArray();

            $analytic = $this->setType(AnalyticType::BestSellingServicePerRoofer)->saveAnalysisResult($analysisResult);
            $analytic->refresh();
        }
        return $analytic->analysis_result;
    }

    /**
     * Get roofer per province
     *
     * @return array
     */
    public function bestRooferPerProvince()
    {
        $company = $this->getCompany();
        $analytic = $this->setType(AnalyticType::BestRooferPerProvince)->getModel();
        if (!$analytic || $this->recalculate) {
            $subQuery = \DB::table("works")->select([
                "employees.id AS employee_id",
                "users.fullname AS fullname",

                "addresses.zipcode AS zipcode",
                "addresses.city AS city",
                "addresses.province AS province",

                \DB::raw("SUM(revenues.amount) AS revenue_sum"),
                \DB::raw("SUM(costs.amount) AS cost_sum"),
                \DB::raw("SUM(revenues.amount) - SUM(costs.amount) AS profit_sum"),
            ])
                ->where("works.company_id", $company->id)
                ->join("workables", "workables.work_id", "=", "works.id")
                ->join("appointments", "appointments.id", "=", "workables.workable_id")
                ->join("appointment_employees", "appointment_employees.appointment_id", "=", "appointments.id")
                ->join("users", "users.id", "=", "appointment_employees.user_id")
                ->join("employees", "employees.user_id", "=", "users.id")
                ->join("customers", "customers.id", "=", "appointments.customer_id")
                ->join("addresses", "addresses.addressable_id", "=", "customers.id")
                ->join("revenueables", "revenueables.revenueable_id", "=", "works.id")
                ->join("revenues", function ($join) {
                    return $join->on("revenues.id", "=", "revenueables.revenue_id")
                        ->whereBetween("revenues.created_at", [$this->start, $this->end]);
                })
                ->join("costables", "costables.costable_id", "=", "appointments.id")
                ->join("costs", function ($join) {
                    return $join->on("costs.id", "=", "costables.cost_id")
                        ->whereBetween("costs.created_at", [$this->start, $this->end]);
                })
                ->groupBy("employee_id")
                ->groupBy("province")
                ->orderBy("profit_sum", "DESC");

            $analysisResult = \DB::table(\DB::raw("({$subQuery->toSql()}) as sub_query"))
                ->mergeBindings($subQuery)
                ->groupBy("employee_id")
                ->orderBy("profit_sum", "DESC")
                ->get([
                    "*",
                    // \DB::raw("MAX(profit_sum)"),
                ])
                ->toArray();

            $analytic = $this->setType(AnalyticType::BestRooferPerProvince)->saveAnalysisResult($analysisResult);
            $analytic->refresh();
        }
        return $analytic->analysis_result;
    }

    /**
     * Get summary
     *
     * @return array
     */
    public function summary()
    {
        $company = $this->getCompany();
        $analytic = $this->setType(AnalyticType::Summary)->getModel();

        if (!$analytic || $this->recalculate) {
            $appointment = Appointment::selectRaw('COUNT(*) AS count')
                ->selectRaw("COUNT(CASE WHEN status = " . AppointmentStatus::Created . " then 1 end) AS created_count")
                ->selectRaw("COUNT(CASE WHEN status = " . AppointmentStatus::InProcess . " then 1 end) AS in_process_count")
                ->selectRaw("COUNT(CASE WHEN status = " . AppointmentStatus::Processed . " then 1 end) AS processed_count")
                ->selectRaw("COUNT(CASE WHEN status = " . AppointmentStatus::Calculated . " then 1 end) AS calculated_count")
                ->where('company_id', $company->id)
                ->when(
                    $this->start && $this->end,
                    fn ($q) => $q->whereBetween('created_at', [$this->start, $this->end])
                )
                ->first()
                ->toArray();

            $customer = Customer::select([
                \DB::raw("AVG(revenues.amount) as worth_avg"),
            ])->where("customers.company_id", $company->id)
                ->join("appointments", "appointments.customer_id", "=", "customers.id")
                ->join(
                    "workables",
                    fn ($join) => $join->on("workables.workable_id", "=", "appointments.id")
                        ->where("workables.workable_type", "=", Appointment::class)
                )
                ->join(
                    "works",
                    fn ($join) => $join->on("works.id", "=", "workables.work_id")
                        ->when(
                            $this->start && $this->end,
                            fn ($q) => $q->whereBetween("works.created_at", [$this->start, $this->end])
                        )
                )
                ->join(
                    "revenueables",
                    fn ($join) => $join->on("revenueables.revenueable_id", "=", "works.id")
                        ->where("revenueables.revenueable_type", "=", Work::class)
                        ->when(
                            $this->start && $this->end,
                            fn ($q) => $q->whereBetween("revenueables.created_at", [$this->start, $this->end])
                        )
                )
                ->join(
                    "revenues",
                    fn ($join) => $join->on("revenues.id", "=", "revenueables.revenue_id")
                        ->when(
                            $this->start && $this->end,
                            fn ($q) => $q->whereBetween("revenueables.created_at", [$this->start, $this->end])
                        )
                )
                ->first()
                ->toArray();

            $invoice = Invoice::selectRaw('COUNT(*) AS count')
                ->selectRaw("COUNT(CASE WHEN status = " . InvoiceStatus::Unpaid . " then 1 end) AS unpaid_count")
                ->selectRaw(
                    "COUNT(CASE WHEN status <= "
                        . InvoiceStatus::Overdue
                        . " AND status >= "
                        . InvoiceStatus::DebtCollectorSent
                        . " then 1 end) AS expired_count"
                )
                ->where('company_id', $company->id)
                ->when(
                    $this->start && $this->end,
                    fn ($q) => $q->whereBetween('created_at', [$this->start, $this->end])
                )
                ->first()
                ->toArray();

            $revenue = Revenue::selectRaw('COUNT(*) AS count')
                ->selectRaw('COALESCE(SUM(amount - paid_amount), 0) AS outstanding_sum')
                ->where('company_id', $company->id)
                ->when(
                    $this->start && $this->end,
                    fn ($q) => $q->whereBetween('created_at', [$this->start, $this->end])
                )
                ->first()
                ->toArray();

            $warranty = Warranty::selectRaw('COUNT(*) AS count')
                ->selectRaw("COUNT(CASE WHEN status = " . WarrantyStatus::Created . " then 1 end) AS open_count")
                ->where('company_id', $company->id)
                ->when(
                    $this->start && $this->end,
                    fn ($q) => $q->whereBetween('created_at', [$this->start, $this->end])
                )
                ->first()
                ->toArray();

            $work = Work::setEagerLoads([])->whereHas("appointments", fn ($q) => $q->whereHas("worklists"))
                ->selectRaw("SUM(total_price) AS planned_total_price_sum")
                ->where('company_id', $company->id)
                ->when(
                    $this->start && $this->end,
                    fn ($q) => $q->whereBetween('created_at', [$this->start, $this->end])
                )
                ->first()
                ->toArray();

            $workday = Workday::selectRaw('COUNT(*) AS count')
                ->selectRaw("COUNT(CASE WHEN status = " . WorkdayStatus::Prepared . " then 1 end) AS prepared_count")
                ->selectRaw("COUNT(CASE WHEN status = " . WorkdayStatus::Processed . " then 1 end) AS processed_count")
                ->selectRaw("COUNT(CASE WHEN status = " . WorkdayStatus::Calculated . " then 1 end) AS calculated_count")
                ->where('company_id', $company->id)
                ->when(
                    $this->start && $this->end,
                    fn ($q) => $q->whereBetween('created_at', [$this->start, $this->end])
                )
                ->first()
                ->toArray();

            $worklist = Worklist::selectRaw('COUNT(*) AS count')
                ->selectRaw("COUNT(CASE WHEN status = " . WorklistStatus::Prepared . " then 1 end) AS prepared_count")
                ->selectRaw("COUNT(CASE WHEN status = " . WorklistStatus::Processed . " then 1 end) AS processed_count")
                ->selectRaw("COUNT(CASE WHEN status = " . WorklistStatus::Calculated . " then 1 end) AS calculated_count")
                ->where('company_id', $company->id)
                ->when(
                    $this->start && $this->end,
                    fn ($q) => $q->whereBetween('created_at', [$this->start, $this->end])
                )
                ->first()
                ->toArray();

            $analysisResult = compact(
                "appointment",
                "customer",
                "invoice",
                "revenue",
                "warranty",
                "work",
                "workday",
                "worklist"
            );

            $analysisResult = array_merge_recursive($analysisResult, [
                "appointment" => [
                    "unpaid_w_slash_o_count" => "Coming Soon"
                ],
                "address" => [
                    "wrong_zip_count" => "Coming Soon"
                ],
            ]);

            $analytic = $this->setType(AnalyticType::Summary)->saveAnalysisResult($analysisResult);
            $analytic->refresh();
        }

        /**
         *  @todo Hidden feature for next release
         *  TODO: Hidden feature for next release
         *
         *  Hidden modules :
         *  - open_warranties
         *  - outstanding_revenue
         *  - unpaid_w_slash_o_count
         *  - planned_work
         *  - appointment
         *  - workday
         *  - worklist
         */

        return $analytic->analysis_result;
    }

    /**
     * Get yesterday's cost and revenue
     *
     * @return array
     */
    public function yesterdayCostRevenue()
    {
        $yesterday = now()->copy()->startOfDay()->subDay()->toDateString();
        $today = now()->copy()->startOfDay()->toDateString();
        $this->setStart($yesterday);
        $this->setEnd($today);

        $company = $this->getCompany();
        $analytic = $this->setType(AnalyticType::SummaryCostRevenue)->getModel();

        if (!$analytic || $this->recalculate) {
            $cost = \DB::select("SELECT SUM(CASE WHEN DATE(created_at)='{$today}' THEN amount ELSE 0 END ) as today_sum,
            SUM(CASE WHEN DATE(created_at)='{$yesterday}' THEN amount ELSE 0 END ) as yesterday_sum
            FROM revenues WHERE company_id ='{$company->id}';")[0] ?? [];

            $revenue = \DB::select("SELECT SUM(CASE WHEN DATE(created_at)='{$today}' THEN amount ELSE 0 END ) as today_sum,
            SUM(CASE WHEN DATE(created_at)='{$yesterday}' THEN amount ELSE 0 END ) as yesterday_sum
            FROM revenues WHERE company_id ='{$company->id}';")[0] ?? [];

            $analysisResult = compact("cost", "revenue");

            $analytic = $this->setType(AnalyticType::SummaryCostRevenue)->saveAnalysisResult($analysisResult);
            $analytic->refresh();
        }
        return $analytic->analysis_result;
    }
}
