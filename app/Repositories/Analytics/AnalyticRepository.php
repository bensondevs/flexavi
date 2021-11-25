<?php

namespace App\Repositories\Analytics;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\Builder;
use App\Repositories\Base\BaseRepository;

use App\Models\{ Revenue, Cost, Analytic };
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
	 * @return void
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
	 * @return void
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
	 * @return void
	 */
	public function currentSemester()
	{
		$semesterStart = now()->copy()->startOfYear()->addMonth(6);
		$this->setStart($semesterStart);
		$this->setEnd(now());
	}

	/**
	 * Set timeframe to last year
	 * 
	 * @return void
	 */
	public function sinceLastYear()
	{
		$this->setStart(now()->copy()->subYear()->startOfDay());
		$this->setEnd(now());
	}

	/**
	 * Create the query groupper for model query select
	 * 
	 * @param string  $column
	 * @return Illuminate\Support\Facades\DB
	 */
	public function createQueryGroupper(string $column)
	{
		if ($this->groupBy == 'month') {
			return \DB::raw('MONTH(created_at) as month');
		} else if ($this->groupBy == 'year') {
			\DB::raw('YEAR(created_at) as year');
		}

		return \DB::raw('DAY(created_at) as day');
	}

	/**
	 * Analyze revenue and prepare data to be modelled
	 * 
	 * @return array
	 */
	public function revenueTrends()
	{
		$start = $this->start;
		$end = $this->end;
		$existingAnalytic = Analytic::revenueTrends()
			->whereStart($start)
			->whereEnd($end)
			->first();
		if ($existingAnalytic) {
			return $existingAnalytic->analysis_result;
		}

		$revenues = Revenue::select($this->createQueryGroupper('created_at'))
			->createdBetween($start, $end)
			->groupBy($this->groupBy)
			->get();

		$model = $this->chartModel;
		$model->setLabels(($revenues->keys())->all());
		$model->addDataset($revenues, 'green', 'green');

		$analytic = new Analytic([
			'analytic_type' => AnalyticType::RevenueTrends,
			'start' => $this->start,
			'end' => $this->end,
		]);
		$analytic->analysis_result = $model->generateChartData();
		$analytic->save();

		return $analytic->analysis_result;
	}

	/**
	 * Generate trend analytic for costs
	 * 
	 * @return bool
	 */
	public function costTrends()
	{
		//
	}

	/**
	 * Generate trend analytic for profits
	 * 
	 * @return bool
	 */
	public function profitTrends()
	{
		//
	}
}