<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use App\Repositories\Base\BaseRepository;

use App\Models\{ Appointment, SubAppointment };
use App\Enums\SubAppointment\SubAppointmentStatus as Status;

class SubAppointmentRepository extends BaseRepository
{
	/**
	 * Repository constructor method
	 * 
	 * @return void
	 */
	public function __construct()
	{
		$this->setInitModel(new SubAppointment);
	}

	/**
	 * Save sub appointment using supplied array input
	 * 
	 * @param  array  $data
	 * @return \App\Models\SubAppointment
	 */
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

	/**
	 * Cancel sub appointment using supplied array input
	 * 
	 * @param  array  $cancellationData
	 * @return \App\Models\SubAppointment
	 */
	public function cancel(array $cancellationData = [])
	{
		try {
			$subAppointment = $this->getModel();
			$subAppointment->status = Status::Cancelled;
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

	/**
	 * Reschedule sub-appointment using supplied array input
	 * 
	 * @param  array  $newSchedule
	 * @return \App\Models\SubAppointment
	 */
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

	/**
	 * Execute sub appointment using supplied array input
	 * 
	 * @return \App\Models\SubAppointment
	 */
	public function execute()
	{
		try {
			$subAppointment = $this->getModel();
			$subAppointment->status = Status::InProcess;
			$subAppointment->save();

			$this->setModel($subAppointment);

			$this->setSuccess('Successfully execute sub-appointment. Now, this sub-appointment is in process.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to execute sub-appointment.', $error);
		}

		return $this->getModel();
	}

	/**
	 * Set sub-appointment status as processed
	 * 
	 * @return \App\Models\SubAppointment
	 */
	public function process()
	{
		try {
			$subAppointment = $this->getModel();
			$subAppointment->status = Status::Processed;
			$subAppointment->save();

			$this->setModel($subAppointment);

			$this->setSuccess('Successfully process sub-appointment. Now, this sub-appointment is processed.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to process sub-appointment.', $error);
		}

		return $this->getModel();
	}

	/**
	 * Delete sub-appointment
	 * 
	 * @param  bool  $force
	 * @return \App\Models\SubAppointment
	 */
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
