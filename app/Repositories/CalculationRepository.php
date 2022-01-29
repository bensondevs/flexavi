<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use App\Repositories\Base\BaseRepository;

use App\Models\{
	Calculation,
	Appointment
};
use App\Http\Resources\{
	CalculationCostResource,
	CalculationWorkResource,
	CalculationRevenueResource
};

class CalculationRepository extends BaseRepository
{
	/**
	 * Cost repository class container
	 * 
	 * @var \App\Repositories\CostRepository|null
	 */
	private $costRepository;

	/**
	 * Revenue repository class container
	 * 
	 * @var \App\Repositories\RevenueRepository|null
	 */
	private $revenueRepository;

	/**
	 * Repository constructor method
	 * 
	 * @return void
	 */
	public function __construct()
	{
		$this->setInitModel(new Calculation);

		$this->costRepository = new CostRepository;
		$this->revenueRepository = new RevenueRepository;
	}

	/**
	 * Make calculation result of appointment
	 * 
	 * @param  \App\Models\Appointment  $appointment
	 * @return \App\Models\Calculation
	 */
	public function calculateAppointment(Appointment $appointment)
	{
		if ($calculation = $appointment->calculation) {
			$calculation->delete();
		}

		// Collect cashflows information
		$costs = $appointment->costs;
		$totalCosts = $costs->sum('amount');
		$totalPaidCosts = $costs->sum('paid_amount');
		$totalUnpaidCosts = $totalCosts - $totalPaidCosts;

		$revenues = $appointment->revenues;
		$totalRevenues = $revenues->sum('amount');
		$totalPaidRevenues = $revenues->sum('paid_amount');
		$totalUnpaidRevenues = $totalRevenues - $totalPaidRevenues;

		// VAT Amount
		$companyVatPercentage = /*$appointment->company->settings->vatPercentage()*/0;
		$totalVat = $totalRevenues * $companyVatPercentage / 100;

		// Gross Profit
		$grossProfit = $totalRevenues - $totalCosts;

		// KPIs
		$durationInDays = $appointment->duration_in_days ?: 1;
		$averageCost = $totalCosts / $durationInDays;
		$averageRevenue = $totalRevenues / $durationInDays;
		$averageProfit = $grossProfit / $durationInDays;

		// Calculation Data
		$calculationData = [
			'costs' => CalculationCostResource::collection($costs),
			'total_costs' => $totalCosts,
			'total_paid_costs' => $totalPaidCosts,
			'total_unpaid_costs' => $totalUnpaidCosts,

			'finished_works' => CalculationWorkResource::collection($appointment->finishedWorks),

			'revenues' => CalculationRevenueResource::collection($revenues),
			'total_revenues' => $totalRevenues,
			'total_paid_revenues' => $totalPaidRevenues,
			'total_unpaid_revenues' => $totalUnpaidRevenues,
			
			'total_vat' => $totalVat,
			
			'gross_profit' => $grossProfit,

			'kpis' => [
				'duration_day' => $durationInDays,
				'average_revenue' => $averageRevenue,
				'average_cost' => $averageCost,
				'average_profit' => $averageProfit,
			]
		];

		try {
			$calculation = $this->getModel();
			$calculation->calculationable_type = get_class($appointment);
			$calculation->calculationable_id = $appointment->id;
			$calculation->calculation = $calculationData;
			$calculation->save();

			$this->setModel($calculation);

			$this->setSuccess('Successfully calculate appointment.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to calculate appointment.', $error);
		}

		return $calculationData;
	}

	/**
	 * Make calculation result for worklist
	 * 
	 * @param  \App\Models\Worklist  $worklist
	 * @return \App\Models\Calculation
	 */
	public function calculateWorklist(Worklist $worklist)
	{
		//
	}

	/**
	 * Make calculation result for workday
	 * 
	 * @param  \App\Models\Workday  $workday
	 * @return \App\Models\Calculation
	 */
	public function calculateWorkday(Workday $workday)
	{
		//
	}

	/**
	 * Delete calculation
	 * 
	 * @param  bool  $force
	 * @return bool
	 */
	public function delete(bool $force = false)
	{
		try {
			$calculation = $this->getModel();
			$force ?
				$calculation->forceDelete() :
				$calculation->delete();

			$this->destroyModel();

			$this->setSuccess('Successfully delete calculation.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to delete calculation.', $error);
		}

		return $this->returnResponse();
	}
}
