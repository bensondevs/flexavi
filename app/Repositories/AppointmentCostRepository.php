<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use App\Repositories\Base\BaseRepository;

use App\Models\{ Appointment, Cost };

class AppointmentCostRepository extends BaseRepository
{
	/**
	 * Repository method constructor
	 * 
	 * @return void
	 */
	public function __construct()
	{
		$this->setInitModel(new Cost);
	}

	/**
	 * Calculate appointment cost total
	 * 
	 * @param   \App\Models\Appointment  $appointment
	 * @return  double
	 */
	public function calculateTotal(Appointment $appointment)
	{
		$costs = $appointment->costs;
		foreach ($costs as $cost) {
			$cost->unpaid_cost = $cost->unpaid_cost;
		}

		return $costs->sum('unpaid_cost');
	}
}
