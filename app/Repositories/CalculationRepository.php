<?php

namespace App\Repositories;

use \Illuminate\Support\Facades\DB;
use \Illuminate\Database\QueryException;

use App\Repositories\Base\BaseRepository;

use App\Repositories\CostRepository;
use App\Repositories\RevenueRepository;

use App\Models\Calculation;
use App\Models\Appointment;

class CalculationRepository extends BaseRepository
{
	private $cost;
	private $revenue;

	public function __construct()
	{
		$this->setInitModel(new Calculation);

		$this->cost = new CostRepository;
		$this->revenue = new RevenueRepository;
	}

	public function calculateAppointment(Appointment $appointment)
	{
		if ($calculation = $appointment->calculation) {
			return $calculation;
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
		$companyVatPercentage = $appointment->company->settings->vatPercentage();
		$totalVat = $totalRevenues * $companyVatPercentage / 100;

		// Gross Profit
		$grossProfit = $totalPaidRevenues - $totalCosts - $totalUnpaidCosts + $totalUnpaidRevenues - $totalVat;

		// KPIs
		$durationInDays = $appointment->durantion_in_days;
		$averageCost = $totalCosts / $durationInDays;
		$averageRevenue = $totalRevenues / $durationInDays;
		$averageProfit = $grossProfit / $durationInDays;

		// Calculation Data
		$calculationData = [
			'costs' => $costs,
			'total_costs' => $totalCosts,
			'total_paid_costs' => $totalPaidCosts,
			'total_unpaid_costs' => $totalUnpaidCosts,

			'revenues' => $revenues,
			'total_revenues' => $totalRevenues,
			'total_paid_revenues' => $totalPaidRevenues,
			'total_unpaid_revenues' => $totalUnpaidRevenues,
			
			'total_vat' => $totalVat,
			
			'gross_profit' => $grossProfit,

			'kpi' => [
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

		return $this->getModel();
	}

	public function calculateWorklist(Worklist $worklist)
	{
		//
	}

	public function calculateWorkday(Workday $workday)
	{
		//
	}

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
