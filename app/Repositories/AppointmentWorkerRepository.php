<?php

namespace App\Repositories;

use \Illuminate\Support\Facades\DB;
use \Illuminate\Database\QueryException;

use App\Repositories\Base\BaseRepository;

use App\Models\Appointment;
use App\Models\AppointmentWorker;

class AppointmentWorkerRepository extends BaseRepository
{
	public function __construct()
	{
		$this->setModel(new AppointmentWorker);
		// $this->setParentModel($appointment);
	}

	public function save(array $workerData)
	{
		try {
			$worker = $this->getModel();
			$worker->fill($workerData);
			$worker->save();
			
			$this->setModel($worker);

			$this->setSuccess('Successfully save appointment worker.');
		} catch (QueryException $qe) {
			$this->setError(
				'Failed to add worker to appointment',
				$qe->getMessage()
			);
		}

		return $this->getModel();
	}

	public function delete(bool $force = false)
	{
		try {
			$worker = $this->getModel();
			$force ?
				$worker->forceDelete() :
				$worker->delete();

			$this->destroyModel();

			$this->setSuccess('Successfully remove worker.');
		} catch (QueryException $qe) {
			$this->setError(
				'Failed to remove worker.', 
				$qe->getMessage()
			);
		}

		return $this->returnResponse();
	}
}
