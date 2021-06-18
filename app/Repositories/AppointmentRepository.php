<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;

use App\Enums\AppointmentableType;

use App\Models\Appointment;
use App\Models\Appointmentable;

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
			$appointment->cancelled = true;
			$appointment->cancellation_cause = $cancelData['cancellation_cause'];

			if ($cancelData['reschedule']) {
				$reschedule = new Appointment();
				$reschedule->fill($cancelData['reschedule_data']);
				$reschedule->previous_appointment_id = $appointment->id;
				$reschedule->save();

				// Move assigned appointment
				$assigned = $appointment->assigned;
				$assigned->appointment_id = $reschedule->id;
				$assigned->save();

				// Next Appointment assign
				$appointment->next_appointment_id = $reschedule->id;
			}

			$appointment->save();
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
			$force ?
				$appointment->forceDelete() :
				$appointment->delete();

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
