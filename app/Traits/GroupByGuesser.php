<?php

namespace App\Traits;

use Closure;
use Illuminate\Database\Query\Expression;

trait GroupByGuesser
{
    /**
     * the Group by callback property
     *
     * @var Closure|null
     */
    private ?Closure $groupByCallback;

    /**
     * the Group by key property
     *
     * @var string|null
     */
    private ?string $groupByKey;


    /**
     * the Group by alias key property
     *
     * @var string|null
     */
    private ?string $groupByAliasKey;

    /**
     * the Group by query property
     *
     * @var Expression|null
     */
    private ?Expression $groupByQuery;

    /**
     * get properties as array
     *
     * @return array
     */
    public function getGroupBy(): array
    {
        return [
            "callback" => $this->groupByCallback,
            "key" => $this->groupByKey,
            "alias_key" => $this->groupByAliasKey,
            "query" => $this->groupByQuery,
        ];
    }

    /**
     * get groupByCallback property
     *
     * @return Closure
     */
    public function getGroupByCallback(): Closure
    {
        return $this->groupByCallback;
    }

    /**
     * Set groupBy property
     *
     * @param Closure $groupByCallback
     * @return static
     */
    public function setGroupByCallback(Closure $groupByCallback): static
    {
        $this->groupByCallback = $groupByCallback;

        return $this;
    }


    /**
     * get  Group by query property
     *
     * @return Expression|null
     */
    public function getGroupByQuery(): ?Expression
    {
        return $this->groupByQuery;
    }

    /**
     * Guess the groupBy by calling input from request helper
     *
     * @return self
     */
    public function guessGroupBy(): static
    {
        switch (request()->input('group_by')) {
            case 'hourly':
            case 'hour':
                $this->groupHourly();
                break;

            case 'daily':
            case 'date':
                $this->groupDaily();
                break;

            case 'monthly':
            case 'month':
                $this->groupMonthly();
                break;

            case 'yearly':
            case 'year':
                $this->groupYearly();
                break;
            default:
                $this->dontGroup();
        }

        return $this;
    }

    /**
     * Group item hourly
     *
     * @return static
     */
    public function groupHourly(): static
    {
        if (is_null($this->groupByAliasKey)) $this->setGroupByAliasKey("hour");
        $this->groupByQuery = \DB::raw("DATE_FORMAT({$this->getGroupByKey()}, '%Y-%m-%d %H:00:00.000') AS {$this->getGroupByAliasKey()}");
        $this->groupByCallback = fn($item) => carbon()
            ->parse($item->{$this->getGroupByKey()} ?? $item[$this->getGroupByKey()])
            ->toTimeString();

        return $this;
    }

    /**
     * get groupBy property
     *
     * @return string
     */
    public function getGroupByKey(): string
    {
        return $this->groupByKey ?? "created_at";
    }

    /**
     * Set groupBy property
     *
     * @param string $groupByKey
     * @return $this
     */
    public function setGroupByKey(string $groupByKey): static
    {
        $this->groupByKey = $groupByKey;

        return $this;
    }

    /**
     * get groupByAlias property
     *
     * @return string
     */
    public function getGroupByAliasKey(): string
    {
        return $this->groupByAliasKey;
    }

    /**
     * Set groupBy alias property
     *
     * @param string $groupByAliasKey
     * @return $this
     */
    public function setGroupByAliasKey(string $groupByAliasKey): static
    {
        $this->groupByAliasKey = $groupByAliasKey;

        return $this;
    }

    /**
     * Group item daily
     *
     * @return static
     */
    public function groupDaily(): static
    {
        if (is_null($this->groupByAliasKey)) $this->setGroupByAliasKey("date");

        $this->groupByQuery = \DB::raw("DATE({$this->getGroupByKey()}) AS {$this->getGroupByAliasKey()}");
        $this->groupByCallback = fn($item) => carbon()
            ->parse($item->{$this->getGroupByKey()} ?? $item[$this->getGroupByKey()])
            ->toDateString();

        return $this;
    }

    /**
     * Group item monthly
     *
     * @return static
     */
    public function groupMonthly(): static
    {
        if (is_null($this->groupByAliasKey)) $this->setGroupByAliasKey("month");

        $this->groupByQuery = \DB::raw("MONTH({$this->getGroupByKey()}) AS {$this->getGroupByAliasKey()}");
        $this->groupByCallback = fn($item) => carbon()
            ->parse($item->{$this->getGroupByKey()} ?? $item[$this->getGroupByKey()])
            ->month;

        return $this;
    }

    /**
     * Group item yearly
     *
     * @return static
     */
    public function groupYearly(): static
    {
        if (is_null($this->groupByAliasKey)) $this->setGroupByAliasKey("year");

        $this->groupByQuery = \DB::raw("YEAR({$this->getGroupByKey()}) AS {$this->getGroupByAliasKey()}");
        $this->groupByCallback = fn($item) => carbon()
            ->parse($item->{$this->getGroupByKey()} ?? $item[$this->getGroupByKey()])
            ->year;

        return $this;
    }

    /**
     * Don't Group item
     *
     * @return static
     */
    public function dontGroup(): static
    {
        $this->groupByQuery = null;
        $this->groupByCallback = null;
        return $this;
    }
}
