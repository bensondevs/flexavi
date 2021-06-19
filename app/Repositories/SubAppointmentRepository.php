<?php

namespace App\Repositories;

use \Illuminate\Support\Facades\DB;
use \Illuminate\Database\QueryException;

use App\Repositories\Base\BaseRepository;

use App\Models\Appointment;
use App\Models\SubAppointment;

class SubAppointmentRepository extends BaseRepository
{
	public function __construct()
	{
		$this->setInitModel(new SubAppointment);
	}

	public function save(array $data)
	{
		try {
			$subAppointment = $this->getModel();
			$subAppointment->fill($data);
			$subAppointment->save();

			$this->setModel($subAppointment);

			$this->setSuccess('Successfully added sub appointment.');
		} catch (QueryException $qe) {
			$error = $qe->getModel();
			$this->setError('Failed to add sub appointment.', $error);
		}

		return $this->getModel();
	}

	public function delete(bool $force = false)
	{
		try {
			$subAppointment = $this->getModel();
			$force ? 
				$subAppointment->forceDelete() :
				$subAppointment->delete();

			$this->destroyModel();

			$this->setSuccess('Successfully delete sub appointment');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to delete sub appointment.', $error);
		}

		return $this->returnResponse();
	}
}
