<?php

namespace App\Repositories;

use \Illuminate\Support\Facades\DB;
use \Illuminate\Database\QueryException;

use App\Repositories\Base\BaseRepository;

use App\Models\Appointment;
use App\Models\Cost;

class AppointmentCostRepository extends BaseRepository
{
	public function __construct()
	{
		$this->setInitModel(new Cost);
	}

	public function calculateTotal(Appointment $appointment)
	{
		$costs = $appointment->costs;
		foreach ($costs as $cost) {
			$cost->unpaid_cost = $cost->unpaid_cost;
		}

		return $costs->sum('unpaid_cost');
	}

	public function save(array $costData = [])
	{
		try {
			$cost = $this->getModel();
			$cost->fill($costData);
			$cost->save();

			$this->setModel($cost);

			$this->setSuccess('Successfully save appointment cost');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to save appointment cost.', $error);
		}

		return $this->getModel();
	}

	public function delete(bool $force = false)
	{
		try {
			$cost = $this->getModel();
			$force ?
				$cost->forceDelete() :
				$cost->delete();

			$this->destroyModel();

			$this->setSuccess('Successfully delete appointment cost.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to delete appointment cost.');
		}

		return $this->returnResponse();
	}
}
