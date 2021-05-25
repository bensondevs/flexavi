<?php

namespace App\Repositories;

use \Illuminate\Support\Facades\DB;
use \Illuminate\Database\QueryException;

use App\Repositories\Base\BaseRepository;

use App\Models\Schedule;

class ScheduleRepository extends BaseRepository
{
	public function __construct()
	{
		$this->setInitModel(new Schedule);
	}

	public function save(array $scheduleData)
	{
		try {
			$schedule = $this->getModel();
			$schedule->fill($scheduleData);
			$schedule->save();

			$this->setModel($schedule);

			$this->setSuccess('Successfully save schedule.');
		} catch (QueryException $qe) {
			$this->setError(
				'Failed to save schedule.', 
				$qe->getMessage()
			);
		}

		return $this->getModel();
	}

	public function delete(bool $force = false)
	{
		try {
			$schedule = $this->getModel();
			$force ? 
				$schedule->forceDelete() : 
				$schedule->delete();

			$this->destroyModel();

			$this->setSuccess('Successfully delete schedule.');
		} catch (QueryException $qe) {
			$this->setError(
				'Failed to delete schedule.', 
				$qe->getMessage()
			);
		}

		return $this->returnResponse();
	}
}
