<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;

use App\Enums\AppointmentableType;

use App\Models\Appointment;
use App\Models\Appointmentable;

use App\Repositories\WorkRepository;
use App\Repositories\ExecuteWorkRepository;

use App\Repositories\Base\BaseRepository;

class AppointmentRepository extends BaseRepository
{
	public function __construct()
	{
		$this->setModel(new Appointment);
	}

	public function save(array $appointmentData)
	{
		try {
			$appointment = $this->getModel();
			$appointment->fill($appointmentData);
			$appointment->save();

			$this->setModel($appointment);

			$this->setSuccess('Successfully save appointment');
		} catch (QueryException $qe) {
			$this->setError(
				'Failed to save appointment.', 
				$qe->getMessage()
			);
		}

		return $this->getModel();
	}

	public function execute()
	{
		try {
			$appointment = $this->getModel();
			$appointment->execute();

			$this->setModel($appointment);

			$this->setSuccess('Successfully execute appointment.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to execute appointment.', $error);
		}

		return $this->getModel();
	}

	public function addWork(array $workData)
	{
		try {
			$appointment = $this->getModel();

			$workRepository = new WorkRepository;
			$work = $workRepository->save($workData);

			$executeWorkRepository = new ExecuteWorkRepository;
			$executeWork = $executeWorkRepository->execute([
				'appointment_id' => $appointment->id,
				'work_id' => $work->id,
			]);

			$this->setSuccess('Successfully add work to appointment.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to add work to appointment');
		}

		return $this->getModel();
	}

	public function process()
	{
		try {
			$appointment = $this->getModel();
			$appointment->process();

			$this->setModel($appointment);

			$this->setSuccess('Successfully process appointment.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed process appointment.', $error);
		}

		return $this->getModel();
	}

	public function cancel(array $cancelData)
	{
		try {
			$appointment = $this->getModel();

			$appointment->cancellation_cause = $cancelData['cancellation_cause'];
			$appointment->cancellation_vault = $cancelData['cancellation_vault'];
			$appointment->cancellation_note = $cancelData['cancellation_note'];
			
			$appointment->cancel();

			$this->setModel($appointment);

			$this->setSuccess('Successfully cancel appointment.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to cancel appointment', $error);
		}

		return $this->getModel();
	}

	public function calculate()
	{
		//
	}

	public function delete(bool $force = false)
	{
		try {
			$appointment = $this->getModel();
			$force ? $appointment->forceDelete() : $appointment->delete();

			$this->destroyModel();

			$this->setSuccess('Successfully delete appointment.');
		} catch (QueryException $qe) {
			$this->setError(
				'Failed to delete appointment.', 
				$qe->getMessage()
			);
		}

		return $this->returnResponse();
	}
}
