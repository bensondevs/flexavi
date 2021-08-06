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

		$costs = $appointment->costs;
		$revenues = $appointment->revenues;

		$totalRevenues = $revenues->sum('amount');
		$totalCosts = $costs->sum('amount');

		// 
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
