<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use App\Repositories\Base\BaseRepository;

use App\Models\{Worklist, Appointment, Appointmentable};

class WorklistRepository extends BaseRepository
{
	/**
	 * Repository constructor method
	 * 
	 * @return void
	 */
	public function __construct()
	{
		$this->setInitModel(new Worklist);
	}

	/**
	 * Save worklist from supplied array input
	 * 
	 * @param  array  $worklistData
	 * @return \App\Models\Worklist
	 */
	public function save(array $worklistData = [])
	{
		try {
			$worklist = $this->getModel();
			$worklist->fill($worklistData);
			$worklist->save();

			$this->setModel($worklist);

			$this->setSuccess('Successfully save worklist.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to save worklist.', $error);
		}

		return $this->getModel();
	}

	/**
	 * Attach appointment to current worklist
	 * 
	 * @param  \App\Models\Appointment
	 * @return \App\Models\Worklist
	 */
	public function attachAppointment(Appointment $appointment)
	{
		try {
			$worklist = $this->getModel();
			$appointmentable = Appointmentable::create([
				'company_id' => $appointment->company_id,
				'appointmentable_id' => $worklist->id,
				'appointmentable_type' => Worklist::class,
				'appointment_id' => $appointment->id,
			]);

			$this->setModel($worklist);

			$this->setSuccess('Successfully attach appointment to worklist.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to attach appointment to worklist', $error);
		}

		return $this->getModel();
	}

	/**
	 * Attach multiple appointments
	 * 
	 * @param  array  $appointmentIds
	 * @return \App\Models\Worklist
	 */
	public function attachManyAppointments(array $appointmentIds)
	{
		try {
			$worklist = $this->getModel();
			
			Appointmentable::attachMany($worklist, $appointmentIds);

			$this->setModel($worklist);

			$this->setSuccess('Successfully attach many appointments to worklist.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to attach many appointment to worklist.', $error);
		}

		return $this->getModel();
	}

	/**
	 * Detach appointment from worklist
	 * 
	 * @param  \App\Models\Appointment  $appointment
	 * @return \App\Models\Worklist
	 */
	public function detachAppointment(Appointment $appointment)
	{
		try {
			$worklist = $this->getModel();
			$worklist->appointments()->detach($appointment);
			$worklist->save();

			$this->setModel($worklist);

			$this->setSuccess('Successfully detach appointment from worklist.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to detach appointment from worklist.', $error);
		}

		return $this->getModel();
	}

	/**
	 * Detach many appointment from worklist
	 * 
	 * @param  array  $appointmentIds
	 * @return \App\Models\Worklist
	 */
	public function detachManyAppointments(array $appointmentIds)
	{
		try {
			$worklist = $this->getModel();
			$worklist->appointments()->detach($appointmentIds);
			$worklist->save();

			$this->setModel($worklist);

			$this->setSuccess('Successfully detach many appointments from worklist.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to detach many appointments from worklist.', $error);
		}

		return $this->getModel();
	}

	/**
	 * Delete all attached appointments of worklist
	 * 
	 * @return \Ap\Models\Worklist
	 */
	public function truncateAppointments()
	{
		try {
			$worklist = $this->getModel();
			$worklist->appointments()->detach();
			$worklist->save();

			$this->setModel($worklist);

			$this->setSuccess('Successfully truncate appointments inside worklist.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to truncate appointments inside worklist.');
		}

		return $this->getModel();
	}

	/**
	 * Process worklist
	 * 
	 * @return \App\Models\Worklist
	 */
	public function process()
	{
		try {
			$worklist = $this->getModel();
			$worklist->process();

			$this->setModel($worklist);

			$this->setSuccess('Successfully process worklist.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to process worklist.', $error);
		}

		return $this->getModel();
	}

	/**
	 * Calculate worklist revenue and cost
	 * 
	 * @return \App\Models\Worklist
	 */
	public function calculate()
	{
		try {
			$worklist = $this->getModel();
			$worklist->calculate();

			$this->setModel($worklist);

			$this->setSuccess('Successfully calculate worklist.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to calculate worklist.', $error);
		}

		return $this->getModel();
	}

	/**
	 * Delete worklist
	 * 
	 * @param  bool  $force
	 * @return bool
	 */
	public function delete(bool $force = false)
	{
		try {
			$worklist = $this->getModel();
			$force ?
				$worklist->forceDelete() :
				$worklist->delete();

			$this->destroyModel();

			$this->setSuccess('Successfully delete worklist.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to delete worklist.', $error);
		}

		return $this->returnResponse();
	}

	/**
	 * Restore soft deleted worklist
	 * 
	 * @return \App\Models\Worklist
	 */
	public function restore()
	{
		try {
			$worklist = $this->getModel();
			$worklist->restore();

			$this->setModel($worklist);

			$this->setSuccess('Successfully restore worklist.');
		} catch (QueryException $e) {
			$error = $qe->getMessage();
			$this->setError('Failed to restore worklist.', $error);
		}

		return $this->getModel();
	}
}
