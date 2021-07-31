<?php

namespace App\Repositories;

use \Illuminate\Support\Facades\DB;
use \Illuminate\Database\QueryException;

use App\Repositories\Base\BaseRepository;

use App\Models\Appointment;
use App\Models\SubAppointment;

use App\Enums\SubAppointment\SubAppointmentStatus;

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

			$this->setSuccess('Successfully save sub-appointment.');
		} catch (QueryException $qe) {
			$error = $qe->getModel();
			$this->setError('Failed to save sub-appointment.', $error);
		}

		return $this->getModel();
	}

	public function cancel(array $cancellationData = [])
	{
		try {
			$subAppointment = $this->getModel();
			$subAppointment->status = SubAppointmentStatus::Cancelled;
			$subAppointment->fill($cancellationData);
			$subAppointment->save();

			$this->setModel($subAppointment);

			$this->setSuccess('Successfully cancel sub-appointment.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to cancel sub-appointment.', $error);
		}

		return $this->getModel();
	}

	public function reschedule(array $newSchedule = [])
	{
		try {
			// Old Sub Appointment
			$subAppointment = $this->getModel();

			// New Sub Appointment
			$newSubAppointment = $subAppointment->replicate();
			$newSubAppointment->previous_sub_appointment_id = $subAppointment->id;
			$newSubAppointment->start = $newSchedule['start'];
			$newSubAppointment->end = $newSchedule['end'];
			$newSubAppointment->push();

			$this->setModel($newSubAppointment);

			$this->setSuccess('Successfully reschedule sub-appointment.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to reschedule sub-appointment.', $error);
		}

		return $this->getModel();
	}

	public function execute()
	{
		try {
			$subAppointment = $this->getModel();
			$subAppointment->status = SubAppointmentStatus::InProcess;
			$subAppointment->save();

			$this->setModel($subAppointment);

			$this->setSuccess('Successfully execute sub-appointment. Now, this sub-appointment is in process.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to execute sub-appointment.', $error);
		}

		return $this->getModel();
	}

	public function process()
	{
		try {
			$subAppointment = $this->getModel();
			$subAppointment->status = SubAppointmentStatus::Processed;
			$subAppointment->save();

			$this->setModel($subAppointment);

			$this->setSuccess('Successfully process sub-appointment. Now, this sub-appointment is processed.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to process sub-appointment.', $error);
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

			$this->setSuccess('Successfully delete sub-appointment.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to delete sub-appointment.', $error);
		}

		return $this->returnResponse();
	}
}
