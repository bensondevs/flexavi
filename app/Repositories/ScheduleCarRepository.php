<?php

namespace App\Repositories;

use \Illuminate\Support\Facades\DB;
use \Illuminate\Database\QueryException;

use App\Repositories\Base\BaseRepository;

class ScheduleCarRepository extends BaseRepository
{
	public function __construct()
	{
		$this->setInitModel(new ScheduleCar);
	}

	public function save(array $scheculeCarData)
	{
		try {
			$scheduleCar = $this->getModel();
			$scheduleCar->fill($scheduleCarData);
			$scheduleCar->save();

			$this->setModel($scheduleCar);

			$this->setSuccess('Successfully save car schedule.');
		} catch (QueryException $qe) {
			$this->setError(
				'Failed to save car schedule.', 
				$qe->getMessage()
			);
		}
	}

	public function delete(bool $force = false)
	{
		try {
			$scheduleCar = $this->getModel();

			$force ?
				$scheduleCar->forceDelete() :
				$scheduleCar->delete();

			$this->destroyModel();

			$this->setSuccess('Successfully delete car schedule.');
		} catch (QueryException $qe) {
			$this->setError(
				'Failed to delete car schedule.', 
				$qe->getMessage()
			);
		}

		return $this->returnResponse();
	}
}
