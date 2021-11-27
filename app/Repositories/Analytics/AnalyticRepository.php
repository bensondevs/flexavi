<?php

namespace App\Repositories\Analytics;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\Builder;
use App\Repositories\Base\BaseRepository;

use App\Models\{ Revenue, Cost, Analytic };
use App\Enums\Analytic\AnalyticType;
use App\Repositories\Analytics\Models\{
	ApexChartModel, ChartJsModel
};

class AnalyticRepository extends BaseRepository
{
	/**
	 * Chart Model
	 * 
	 * @var mixed
	 */
	private $chartModel;

	/**
	 * Groupper type
	 * 
	 * @var string
	 */
	private $groupBy;

	/**
	 * Time range start of analytic population
	 * 
	 * @var \DateTime
	 */
	private $start;

	/**
	 * Time range end of analytic population
	 * 
	 * @var \DateTime
	 */
	private $end;

	/**
	 * Analytic result
	 * 
	 * @var array
	 */
	private $result;

	/**
	 * Repository constructor method
	 * 
	 * @param mixed  $start
	 * @param mixed  $end
	 * @return void
	 */
	public function __construct($start = null, $end = null)
	{
		$this->groupBy = 'month';
		$this->chartModel = new ChartJsModel;
		$this->setStart($start ?: now()->copy()->addMonth(-3));
		$this->setEnd(now()->copy());
	}

	/**
	 * Set Chart Model to Chart JS
	 * 
	 * @return void 
	 */
	public function useChartJs()
	{
		$this->chartModel = new ChartJsModel;
	}

	/**
	 * Set Chart Model to Apex Chart
	 * 
	 * @return void
	 */
	public function useApexChart()
	{
		$this->chartModel = new ApexChartModel;
	}

	/**
	 * Set date start of the analytics
	 * 
	 * @param mixed  $start
	 * @return $this
	 */
	public function setStart($start)
	{
		$this->start = carbon()->parse($start);

		return $this;
	}

	/**
	 * Set date end of the analytics
	 * 
	 * @param mixed  $end
	 * @return $this
	 */
	public function setEnd($end)
	{
		$this->end = carbon()->parse($end);

		return $this;
	}

	/**
	 * Set timeframe to last 7 days until now
	 * 
	 * @return $this
	 */
	public function sinceLastWeek()
	{
		$this->setStart(now()->copy()->addDays(-7)->startOfDay());
		$this->setEnd(now());

		return $this;
	}

	/**
	 * Set timeframe to last 14 days until now
	 * 
	 * @return $this
	 */
	public function sinceTwoWeeksAgo()
	{
		$this->setStart(now()->copy()->addDays(-14)->startOfDay());
		$this->setEnd(now());

		return $this;
	}

	/**
	 * Set timeframe to last 30 days until now
	 * 
	 * @return $this
	 */
	public function sinceLastMonth()
	{
		$this->setStart(now()->copy()->addDays(-30)->startOfDay());
		$this->setEnd(now());

		return $this;
	}

	/**
	 * Set timeframe to last 90 days until now
	 * 
	 * @return $this
	 */
	public function currentQuarter()
	{
		$this->setStart(now()->copy()->firstOfQuarter());
		$this->setEnd(now());

		return $this;
	}
	

	/**
	 * Set timeframe from start of quarter until now
	 * 
	 * @return $this
	 */
	public function sinceLastTrimester()
	{
		$this->setStart(now()->copy()->addDays(-90)->startOfDay());
		$this->setEnd(now());

		return $this;
	}

	/**
	 * Set timeframe from start of semester till now
	 * 
	 * @return $this
	 */
	public function currentSemester()
	{
		$semesterStart = now()->copy()->startOfYear()->addMonth(6);
		$this->setStart($semesterStart);
		$this->setEnd(now());

		return $this;
	}

	/**
	 * Set timeframe to last year
	 * 
	 * @return $this
	 */
	public function sinceLastYear()
	{
		$this->setStart(now()->copy()->subYear()->startOfDay());
		$this->setEnd(now());

		return $this;
	}

	/**
	 * Guess the date range by calling input from request helper
	 * 
	 * @return $this
	 */
	public function guessDateRange()
	{
		if (request()->has('start') && request()->has('end')) {
			$this->setStart(request()->input('start'));
			$this->setEnd(request()->input('end'));
		}

		switch (request()->input('type')) {
			case 'last_week':
				return $this->sinceLastWeek();
				break;
			case 'last_two_weeks':
				return $this->sinceTwoWeeksAgo();
				break;
			case 'last_couple_weeks':
				return $this->sinceTwoWeeksAgo();
				break;
			case 'last_month':
				return $this->sinceLastMonth();
				break;
			case 'current_month':
				return $this->sinceLastMonth();
				break;
			case 'current_quarter':
				return $this->currentQuarter();
				break;
			case '90_days':
				return $this->currentQuarter();
				break;
			case 'last_trimester':
				return $this->sinceLastTrimester();
				break;
			case 'current_semester':
				return $this->currentSemester();
				break;
			case 'last_year':
				return $this->sinceLastYear();
				break;
			
			default:
				return $this->sinceLastWeek();
				break;
		}

		return $this;
	}

	/**
	 * Save result to database
	 * 
	 * @param array  $result
	 * @return \App\Models\Analytic
	 */
	private function saveResult(array $result)
	{
		$analytic = new Analytic([
			'company_id' => auth()->user()->company->id,
			'start' => $this->start,
			'end' => $this->end,
		]);
		$analytic->analysis_result = $result;
		$analytic->save();

		return $analytic;
	}

	/**
	 * Analyze revenue and prepare data to be modelled
	 * 
	 * @param bool  $forceRecount
	 * @return array
	 */
	public function revenueTrends(bool $forceRecount = false)
	{
		$analytic = Analytic::revenueTrends()
			->whereDate('start', $this->start)
			->whereDate('end', $this->end)
			->first();
		if ((! $analytic) || $forceRecount) {
			$revenues = Revenue::selectRaw('SUM(amount) AS total, MONTHNAME(created_at) AS month')
				->createdBetween($this->start, $this->end)
				->groupByRaw('MONTHNAME(created_at)')
				->get();

			$formattedRevenues = [];
			foreach ($revenues as $revenue) {
				$formattedRevenues[$revenue->month] = $revenue->total;
			}

			// Save result and return analytic model
			$analytic = $this->saveResult($formattedRevenues);
		}

		return $analytic->analysis_result;
	}

	/**
	 * Generate trend analytic for costs
	 * 
	 * @param bool  $forceRecount
	 * @return array
	 */
	public function costTrends(bool $forceRecount = false)
	{
		$analytic = Analytic::costTrends()
			->whereDate('start', $this->start)
			->whereDate('end', $this->end)
			->first();
		if ((! $analytic) || $forceRecount) {
			$costs = Cost::selectRaw('SUM(amount) AS total, MONTHNAME(created_at) AS month')
				->createdBetween($this->start, $this->end)
				->groupByRaw('MONTHNAME(created_at)')
				->get();

			$formattedCosts = [];
			foreach ($costs as $cost) {
				$formattedCosts[$cost->month] = $cost->total;
			}

			// Save result and return analytic model
			$analytic = $this->saveResult($formattedRevenues);
		}

		return $analytic->analysis_result;
	}

	/**
	 * Generate trend analytic for profits
	 * 
	 * @return bool
	 */
	public function profitTrends()
	{
		$analytic = Analytic::profitTrends()
			->whereDate('start', $this->start)
			->whereDate('end', $this->end)
			->first();
	}
}