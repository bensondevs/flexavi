<?php

namespace App\Repositories;

use \Illuminate\Support\Facades\DB;
use \Illuminate\Database\QueryException;

use App\Repositories\Base\BaseRepository;

use App\Models\Work;
use App\Models\Appointment;
use App\Models\SubAppointment;
use App\Models\Quotation;

class WorkRepository extends BaseRepository
{
	/**
	 * Create New Repository Instance
	 * 
	 * @return void
	 */
	public function __construct()
	{
		$this->setInitModel(new Work);
	}

	/**
	 * Get all appointment works
	 * 
	 * @param  array  $appointment
	 * @return Collection
	 */
	public function appointmentWorks(
		Appointment $appointment, 
		array $options = [], 
		bool $paginate = false
	) {
		$works = $appointment->works;
		$this->setModel($works);
		return $this->all($options, $paginate, true);
	}

	/**
	 * Get all sub-appointment works
	 * 
	 * @param  array  $appointment
	 * @return Collection
	 */
	public function subAppointmentWorks(
		SubAppointment $subAppointment, 
		array $options = [], 
		bool $paginate = false
	) {
		$works = $subAppointment->works;
		$this->setModel($works);
		return $this->all($options, $paginate, true);
	}

	/**
	 * Get all sub-appointment works
	 * 
	 * @param  array  $appointment
	 * @return Collection
	 */
	public function quotationWorks(
		Quotation $quotation,
		array $options = [],
		bool $paginate = false
	) {
		$works = $quotation->works;
		$this->setModel($works);
		return $this->all($options, $paginate, true);
	}

	/**
	 * Save work model to database
	 * 
	 * @param  array  $workData
	 * @return \App\Models\Work
	 */
	public function save(array $workData)
	{
		try {
			$work = $this->getModel();
			$work->fill($workData);
			$work->save();

            $this->setModel($work);

			$this->setSuccess('Successfully save work.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to create work.', $error);
		}

		return $this->getModel();
	}

	/**
	 * Attach work model to any work attachable model 
	 * 
	 * @param   mixed  $workable
	 * @return  \App\Models\Work
	 */
	public function attachTo($workable)
	{
		try {
			$work = $this->getModel();
			$work->{get_plural_lower_class($workable)}()->attach($workable);

			$this->setModel($work);

			$this->setSuccess('Successfully attach work.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to attach work.', $error);
		}

		return $this->getModel();
	}

	/**
	 * Attach many works instance to any work attachable
	 * 
	 * @param  mixed  $workable
	 * @param  bool
	 */
	public function attachToMany($workable, array $works)
	{
		try {
			$workable->works()->attach($works);

			$this->setSuccess('Successfully attach many works.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to attach many works.', $error);
		}

		return $this->returnResponse();
	}

	/**
	 * Detach work instance from any work attachable
	 * 
	 * @param  mixed  $workable
	 * @return  \App\Models\Work
	 */
	public function detachFrom($workable)
	{
		try {
			$work = $this->getModel();
			$workable->works()->detach($work);

			$this->setModelSuccess($work, 'Successfully detach work.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to detach work.', $error);
		}

		return $this->getModel();
	}

	/**
	 * Detach many work instances from any work attachable
	 * 
	 * @param  mixed  $workable
	 * @param  array  $works
	 */
	public function detachManyFrom($workable, $works)
	{
		try {
			$workable->works()->detach($works);
			$this->setSuccess('Successfully detach many works.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to detach many works.', $error);
		}

		return $this->returnResponse();
	}

	/**
	 * Truncate work instances attached to any work attachable 
	 * 
	 * @param  mixed  $workable
	 */
	public function truncate($workable)
	{
		try {
			$workable->works()->detach();
			$this->setSuccess('Successfully truncate works.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to truncate works.', $error);
		}

		return $this->returnResponse();
	}

	/**
	 * Set work status to "InProcess"
	 * 
	 * @param  \App\Models\Appointment  $appointment
	 * @return \App\Models\Work
	 */
	public function execute(Appointment $appointment, array $executionData = [])
	{
		try {
			DB::beginTransaction();

			// Execute work
			$work = $this->getModel();
			$work->execute($appointment);

			// Record execution
			$executionData['company_id'] = $work->company_id;
			$executionData['work_id'] = $work->id;
			$executionData['appointment_id'] = $appointment->id;
			$executeWorkRepository = new ExecuteWorkRepository();
			$executeWorkRepository->execute($executionData);

			DB::commit();
		} catch (QueryException $qe) {
			DB::rollBack();

			$error = $qe->getMessage();
			$this->setError('Failed to execite work.', $error);
		}

		return $this->getModel();
	}

	/**
	 * Set work status to "Unfinished"
	 * 
	 * @param  string|null  $unfinishNote
	 * @return \App\Models\Work
	 */
	public function markUnfinish(string $unfinishNote = '')
	{
		try {
			$work = $this->getModel();
			$work->markUnfinished($unfinishNote);

			$this->setModel($work);

			$this->setSuccess('Successfully mark work as unfinished.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to mark work as unfinished.', $error);
		}

		return $this->getModel();
	}

	/**
	 * Set work status to "Finished"
	 * 
	 * @param  string|null  $finishNote
	 * @return \App\Models\Work
	 */
	public function markFinish(Appointment $appointment, string $finishNote = '')
	{
		try {
			$work = $this->getModel();
			$work->markFinished($appointment, $finishNote);

			$this->setModel($work);

			$this->setSuccess('Successfully mark work as finished.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to mark work as finsihed.', $error);
		}

		return $this->getModel();
	}

	/**
	 * Delete record of the model set in repository
	 * 
	 * @param  bool  $force
	 * @return bool
	 */
	public function delete(bool $force = false)
	{
		try {
			$work = $this->getModel();
			$quotation = $work->quotation;
			$force ? $work->forceDelete() : $work->delete();

			$this->destroyModel();

			$this->setSuccess('Successfully delete work.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to delete work.', $error);
		}

		return $this->returnResponse();
	}

	/**
	 * Restore record of the model set in repository
	 * 
	 * @param  bool  $force
	 * @return bool
	 */
	public function restore()
	{
		try {
			$work = $this->getModel();
			$work->restore();

			$this->setModel($work);

			$this->setSuccess('Successfully restore work.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to restore work.', $error);
		}

		return $this->getModel();
	}
}
