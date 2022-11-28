<?php

namespace App\Traits;

use Carbon\Carbon;

trait DateRangeGuesser
{
    /**
     * Time range start of analytic population
     *
     * @var Carbon|null
     */
    private ?Carbon $start = null;

    /**
     * Time range end of analytic population
     *
     * @var Carbon|null
     */
    private ?Carbon $end = null;

    /**
     * Guess the date range by calling input from request helper
     *
     * @return $this
     */
    public function guessDateRange(): static
    {

        switch (request()->input('timeframe')) {
            case 'current_week':
            case 'this_week':
                $this->currentWeek();
                break;
            case 'last_week':
                $this->lastWeek();
                break;

            case 'last_two_weeks':
            case 'last_couple_weeks':
                $this->lastTwoWeeks();
                break;

            case 'current_month':
            case 'this_month':
                $this->currentMonth();
                break;

            case 'last_month':
                $this->lastMonth();
                break;

            case 'current_quarter':
            case 'this_quarter':
                $this->currentQuarter();
                break;

            case 'last_quarter':
                $this->lastQuarter();
                break;

            case 'current_semester':
            case 'this_semester':
                $this->currentSemester();
                break;
            case 'last_semester':
                $this->lastSemester();
                break;

            case 'current_year':
            case 'this_year':
                $this->currentYear();
                break;

            case 'last_year':
                $this->lastYear();
                break;

            default:
                $this->setStart(request()->input(
                    'start',
                    now()->copy()->subDays(7)
                ));
                $this->setEnd(
                    request()->input('end', now())
                );
        }


        return $this;
    }

    /**
     * Get timeframe from current week till now
     *
     * @return $this
     */
    public function currentWeek(): static
    {
        $this->start = now()
            ->copy()
            ->startOfWeek();

        $this->end = now();
        return $this;
    }

    /**
     * Get timeframe to last 7 days until now
     *
     * @return $this
     */
    public function lastWeek(): static
    {
        $this->start =
            now()
                ->copy()
                ->subDays(7)
                ->startOfDay();

        $this->end = now();
        return $this;
    }

    /**
     * Get timeframe to last 14 days until now
     *
     * @return $this
     */
    public function lastTwoWeeks(): static
    {
        $this->start =
            now()
                ->copy()
                ->subDays(14)
                ->startOfDay();

        $this->end = now();
        return $this;
    }

    /**
     * Get timeframe from current month till now
     *
     * @return $this
     */
    public function currentMonth(): static
    {
        $this->start =
            now()
                ->copy()
                ->startOfMonth();

        $this->end = now();
        return $this;
    }

    /**
     * Get timeframe to last 30 days until now
     *
     * @return $this
     */
    public function lastMonth(): static
    {
        $this->start =
            now()
                ->copy()
                ->subDays(30)
                ->startOfDay();

        $this->end = now();
        return $this;
    }

    /**
     * Get timeframe to last 90 days until now
     *
     * @return $this
     */
    public function currentQuarter(): static
    {
        $this->start =
            now()
                ->copy()
                ->firstOfQuarter();

        $this->end = now();
        return $this;
    }

    /**
     * Get timeframe from start of quarter until now
     *
     * @return $this
     */
    public function lastQuarter(): static
    {
        $this->start =
            now()
                ->copy()
                ->subDays(90)
                ->startOfDay();

        $this->end = now();
        return $this;
    }

    /**
     * Get timeframe from start of semester till now
     *
     * @return $this
     */
    public function currentSemester(): static
    {
        $this->start =
            now()
                ->copy()
                ->startOfYear()
                ->addMonths(6);

        $this->end = now();
        return $this;
    }

    /**
     * Get timeframe to last semester
     *
     * @return $this
     */
    public function lastSemester(): static
    {
        $this->start =
            now()
                ->copy()
                ->startOfYear()
                ->addMonths(6);

        $this->end = now();
        return $this;
    }

    /**
     * Get timeframe to current year
     *
     * @return $this
     */
    public function currentYear(): static
    {
        $this->start =
            now()
                ->copy()
                ->subYear()
                ->startOfDay();

        $this->end = now();
        return $this;
    }

    /**
     * Get timeframe to last year
     *
     * @return $this
     */
    public function lastYear(): static
    {
        $this->start =
            now()
                ->copy()
                ->subYear()
                ->startOfDay();

        $this->end = now();
        return $this;
    }

    /**
     * Set date start of the analytics
     *
     * @param mixed $start
     * @return $this
     */
    public function setStart(mixed $start): static
    {
        $this->start = carbon()->parse($start);

        return $this;
    }

    /**
     * Set date end of the analytics
     *
     * @param mixed $end
     * @return $this
     */
    public function setEnd(mixed $end): static
    {
        $this->end = carbon()->parse($end);

        return $this;
    }
}
